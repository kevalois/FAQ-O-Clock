<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Answer;
use App\Entity\UserAnswerVote;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserAnswerVoteRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class AnswerController extends Controller
{
    /**
     * @Route("/answer/validate/{id}", name="answer_validate")
     */
    public function validate(Answer $answer)
    {
        // L'auteur de la question est-il le user qui valide ?
        $user = $this->getUser();
        if ($user !== $answer->getQuestion()->getUser()) {
            throw $this->createAccessDeniedException('Non autorisé.');
        }

        // Valide réponse
        $answer->setIsValidated(true);
        // Valide question
        $answer->getQuestion()->setIsSolved(true);
        // Flush
        $this->getDoctrine()->getEntityManager()->flush();
        // Flash
        $this->addFlash('success', 'Réponse acceptée');
        // Redirection
        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }

    /**
     * @Route("/admin/answer/toggle/{id}", name="admin_answer_toggle")
     */
    public function adminToggle(Answer $answer = null)
    {
        if (null === $answer) {
            throw $this->createNotFoundException('Réponse non trouvée.');
        }

        // Inverse the boolean value via not (!)
        $answer->setIsBlocked(!$answer->getIsBlocked());
        // Save
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Réponse modérée.');

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }

    /**
     * @Route("/answer/vote/{id}", name="answer_vote")
     */
    public function vote(Answer $answer = null, EntityManagerInterface $em, UserAnswerVoteRepository $uavr)
    {
        if (null === $answer) {
            throw $this->createNotFoundException('Réponse non trouvée.');
        }

        $user = $this->getUser();

        $answerVote = new UserAnswerVote();
        $answerVote->setUser($user);
        $answerVote->setAnswer($answer);

        $em->persist($answerVote);
        try {
            $em->flush();
            $this->addFlash('success', 'Réponse votée.');
            // On update le nombre de vote à cette réponse
            $nbVotes = count($uavr->findBy(['answer' => $answer]));
            $answer->setVotes($nbVotes);
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('danger', 'Vous avez déjà voté pour cette réponse.');
        }

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);

    }
}
