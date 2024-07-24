<?php

declare(strict_types=1);

namespace Faster\Http\Session;

/**
 * Session
 * -----------
 * Session
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Session
 */
class Session implements SessionInterface
{
    const FLASH = '__FLASH_SESSION';
    const CSRF = '__CSRF_SESSION';
    const CAPTCHA = '__CAPTCHA_SESSION';

    /**
     * @inheritdoc
     */
    public function set(string $property, $value): void
    {
        $_SESSION[$property] = $value;
    }
    /**
     * @inheritdoc
     */
    public function csrfToken(string $name, bool $generate = true): string
    {
        if ($generate || empty($_SESSION[self::CSRF][$name])) {
            $_SESSION[self::CSRF][$name] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF][$name];
    }
    /**
     * @inheritdoc
     */
    public function validateCsrfToken(string $name, ?string $token): bool
    {
        $result = hash_equals($token, $this->csrfToken($name, false));
        unset($_SESSION[self::CSRF][$name]);

        return $result;
    }
    /**
     * @inheritdoc
     */
    public function captcha(string $formName, int $length = 6, bool $generate = true): string
    {
        if ($generate || empty($_SESSION[self::CAPTCHA][$formName])) {
            $captchaString = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $captcha =  \substr(\str_shuffle($captchaString), 0, $length);
            $_SESSION[self::CAPTCHA][$formName] = $captcha;
        }

        return $_SESSION[self::CAPTCHA][$formName];
    }
    /**
     * @inheritdoc
     */
    public function get(?string $property = null, $defaultValue = null)
    {
        if ($property === null) {
            return $_SESSION ?? $defaultValue;
        } else {
            return $this->has($property) ? $_SESSION[$property] : $defaultValue;
        }
    }
    /**
     * @inheritdoc
     */
    public function validateCaptcha(string $formName, ?string $captcha): bool
    {
        $result = ($captcha === $_SESSION[self::CAPTCHA][$formName]);
        unset($_SESSION[self::CAPTCHA][$formName]);

        return $result;
    }
    /**
     * @inheritdoc
     */
    public function has(?string $property = null): bool
    {
        return $property === null ? isset($_SESSION) : isset($_SESSION[$property]);
    }
    /**
     * @inheritdoc
     */
    public function unset(?string $property = null)
    {
        $value = $this->get($property);
        if ($property) {
            unset($_SESSION[$property]);
        } else {
            $_SESSION = [];
        }

        return $value;
    }
    /**
     * @inheritdoc
     */
    public function addFlashInfo(string $message): void
    {
        $this->addFlash(FlashMessage::INFO, $message);
    }
    /**
     * @inheritdoc
     */
    public function flashInfo(): ?FlashMessage
    {
        return $this->flash(FlashMessage::INFO);
    }
    /**
     * @inheritdoc
     */
    public function addFlashError(string $message): void
    {
        $this->addFlash(FlashMessage::ERROR, $message);
    }
    /**
     * @inheritdoc
     */
    public function flashError(): ?FlashMessage
    {
        return $this->flash(FlashMessage::ERROR);
    }
    /**
     * @inheritdoc
     */
    public function addFlashWarning(string $message): void
    {
        $this->addFlash(FlashMessage::WARNING, $message);
    }
    /**
     * @inheritdoc
     */
    public function flashWarning(): ?FlashMessage
    {
        return $this->flash(FlashMessage::WARNING);
    }
    /**
     * @inheritdoc
     */
    public function addFlashSuccess(string $message): void
    {
        $this->addFlash(FlashMessage::SUCCESS, $message);
    }
    /**
     * @inheritdoc
     */
    public function flashSuccess(): ?FlashMessage
    {
        return $this->flash(FlashMessage::SUCCESS);
    }
    /**
     * @inheritdoc
     */
    public function addFlash(string $type, string $message): void
    {
        $flash = make(FlashMessage::class, ['args' => [$type], 'shared' => false]);
        if (!isset($_SESSION[self::FLASH][$type])) {
            $_SESSION[self::FLASH][$type] = $flash;
        } else {
            $flash = $_SESSION[self::FLASH][$type];
        }
        $flash->addMessage($message);
    }
    /**
     * @inheritdoc
     */
    public function flash(?string $type = null)
    {
        if ($type === null) {
            $flash = $this->unset(self::FLASH);
        } else {
            $flash = $_SESSION[self::FLASH][$type];
            unset($_SESSION[self::FLASH][$type]);
        }
        return $flash;
    }
    /**
     * @inheritdoc
     */
    public function hasFlash(?string $type = null): bool
    {
        return $type === null ? isset($_SESSION[self::FLASH]) : isset($_SESSION[self::FLASH][$type]);
    }
    /**
     * @inheritdoc
     */
    public function hasFlashSuccess(): bool
    {
        return $this->hasFlash(FlashMessage::SUCCESS);
    }
    /**
     * @inheritdoc
     */
    public function hasFlashError(): bool
    {
        return $this->hasFlash(FlashMessage::ERROR);
    }
    /**
     * @inheritdoc
     */
    public function hasFlashWarning(): bool
    {
        return $this->hasFlash(FlashMessage::WARNING);
    }
    /**
     * @inheritdoc
     */
    public function hasFlashInfo(): bool
    {
        return $this->hasFlash(FlashMessage::INFO);
    }
}
