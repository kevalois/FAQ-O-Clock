<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Form\QuestionType;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Entity\UserQuestionVote;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Repository\UserQuestionVoteRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuestionController extends Controller
{
    /**
     * @Route("/{page<\d+>}", name="question_list", defaults={"page"="0"})
     * @Route("/tag/{name}/{page}", name="question_list_by_tag")
     * @ParamConverter("tag", class="App:Tag")
     */
    public function list(Request $request, QuestionRepository $questionRepository, Tag $tag = null, AuthorizationCheckerInterface $authChecker, $page = 0)
    {
        // On vérifie si on vient de la route "question_list_by_tag"
        if ($request->attributes->get('_route') == 'question_list_by_tag' && $tag === null) {
            // On récupère le name passé dans l'attribut de requête
            $params = $request->attributes->get('_route_params');
            $selectedTag = $params['name'];
            // Equivaut à $selectedTag = $request->attributes->get('_route_params')['name'];

            // Flash + redirect
            $this->addFlash('success', 'Le mot-clé "'.$selectedTag.'" n\'existe pas. Affichage de toutes les questions.');
            return $this->redirectToRoute('question_list');
        } else {
            // Gestion du tag sélectionné
            $selectedTag = isset($tag) ? $tag->getName() : null;
        }

        // Questions non bloquées visibles par modérateur
        if ($authChecker->isGranted('ROLE_MODERATOR')) {
            $blockedFilter = false;
        } else {
            $blockedFilter = true;
        }

        // Gestion pagination
        $start = $page;
        $perPage = $this->getParameter('questionsPerPage');
        // Requête générique questions
        $questions = $questionRepository->findByIsBlockedAndTagOrderByVotes($blockedFilter, $tag, $start, $perPage);
        // Pagination du template
        $pagination = [
            'start' => $page,
            'nbPages' => (int) ceil(count($questions) / $perPage),
        ];

        // Nuage de mots-clés
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findBy([], ['name' => 'ASC']);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'tags' => $tags,
            'selectedTag' => $selectedTag,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/question/{id}", name="question_show", requirements={"id": "\d+"})
     */
    public function show(Question $question, Request $request, UserRepository $userRepository, AnswerRepository $answerRepository, AuthorizationCheckerInterface $authChecker)
    {
        // Is question blocked ?
        if ($question->getIsBlocked()) {
            throw $this->createAccessDeniedException('Non autorisé.');
        }

        $answer = new Answer();

        $form = $this->createForm(AnswerType::class, $answer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $answer = $form->getData();
            // On associe Réponse
            $answer->setQuestion($question);

            // User : pour le moment, allons chercher un user issue de notre liste
            $user = $userRepository->findOneByUsername('jc');
            // On associe user
            $answer->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            $this->addFlash('success', 'Réponse ajoutée');

            return $this->redirectToRoute('question_show', ['id' => $question->getId()]);
        }

        // Réponses non bloquées visible par modérateur
        if ($authChecker->isGranted('ROLE_MODERATOR')) {
            $blockedCriteria = [
                'question' => $question,
            ];
        } else {
            $blockedCriteria = [
                'question' => $question,
                'isBlocked' => false,
            ];
        }

        $answersNonBlocked = $answerRepository->findBy($blockedCriteria, [
            'isValidated' => 'DESC',
            'votes' => 'DESC',
        ]);

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answersNonBlocked' => $answersNonBlocked,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/question/add", name="question_add")
     */
    public function add(Request $request, UserRepository $userRepository)
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();

            // User : pour le moment, allons chercher un user issue de notre liste
            $user = $userRepository->findOneByUsername('jc');
            // On associe
            $question->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question ajoutée');

            return $this->redirectToRoute('question_show', ['id' => $question->getId()]);
        }

        return $this->render('question/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/question/toggle/{id}", name="admin_question_toggle")
     */
    public function adminToggle(Question $question = null)
    {
        if (null === $question) {
            throw $this->createNotFoundException('Question non trouvée.');
        }

        // Inverse the boolean value via not (!)
        $question->setIsBlocked(!$question->getIsBlocked());
        // Save
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Question modérée.');

        return $this->redirectToRoute('question_show', ['id' => $question->getId()]);
    }

    /**
     * @Route("/question/vote/{id}", name="question_vote", methods={"GET|POST"})
     */
    public function vote(Question $question = null, EntityManagerInterface $em, UserQuestionVoteRepository $uqvr)
    {
        if (null === $question) {
            // JSON si 404
            return new JsonResponse([
                'error' => true,
                'message' => 'Question non trouvée.',
                'data' => null,
            ]);
        }

        $user = $this->getUser();

        $questionVote = new UserQuestionVote();
        $questionVote->setUser($user);
        $questionVote->setQuestion($question);

        $em->persist($questionVote);
        try {
            $em->flush();
            // On update le nombre de vote à cette question
            $nbVotes = count($uqvr->findBy(['question' => $question]));
            $question->setVotes($nbVotes);
            $em->flush();
            // JSON si success
            return new JsonResponse([
                'error' => false,
                'message' => 'Question votée.',
                'question' => [
                    'id' => $question->getId(),
                    'votes' => $question->getVotes(),
                ],
            ]);

        } catch (UniqueConstraintViolationException $e) {
            // JSON si déjà voté
            return new JsonResponse([
                'error' => true,
                'message' => 'Vous avez déjà voté pour cette question.',
                'data' => null,
            ]);
        }
    }
}
