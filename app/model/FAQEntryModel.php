<?php
namespace model;

use core\IModel;

class FAQEntryModel implements IModel{
    private $faqid;
    private $answer;
    private $question;

    public function __construct($faqid, $answer, $question) {
        $this->faqid = $faqid;
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getFaqid()
    {
        return $this->faqid;
    }

    /**
     * @param mixed $faqid
     */
    public function setFaqid($faqid): void
    {
        $this->faqid = $faqid;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }


    public function __toString() {
        return "FAQEntryModel{" .
            "faqid=" . $this->faqid .
            ", answer='" . $this->answer . '\'' .
            ", question='" . $this->question . '\'' .
            '}';
    }
    public static function toObject($row){
        return new FAQEntryModel(
            $row['faqid'],
            $row['answer'],
            $row['question']
        );
    }

    public static function toArray($obj){
        return [
            'faqid' => $obj->getFaqid(),
            'answer' => $obj->getAnswer(),
            'question' => $obj->getQuestion()
        ];
    }
}