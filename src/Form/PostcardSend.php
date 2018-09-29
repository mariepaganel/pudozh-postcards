<?php
/**
 * Класс формирования готовой открытки перед отправкой
 */

namespace App\Form;


class PostcardSend
{
    private $toName;
    private $toEmail;
    private $fromName;
    private $fromEmail;
    private $content;

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @param mixed $toName
     */
    public function setToName($toName): void
    {
        $this->toName = $toName;
    }

    /**
     * @return mixed
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @param mixed $toEmail
     */
    public function setToEmail($toEmail): void
    {
        $this->toEmail = $toEmail;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param mixed $fromEmail
     */
    public function setFromEmail($fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }
}