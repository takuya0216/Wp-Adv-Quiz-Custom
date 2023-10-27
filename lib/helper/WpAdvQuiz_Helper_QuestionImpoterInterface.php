<?php

interface WpAdvQuiz_Helper_QuestionImpoterInterface
{

    /**
     * @return array
     */
    public function getQuestionPreview();

    /**
     * @param $quizId
     * @return bool
     */
    public function import($quizId);
}
