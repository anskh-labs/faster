<?php

declare(strict_types=1);

namespace Faster\Http\Session;

/**
 * SessionInterface
 * -----------
 * SessionInterface
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Session
 */
interface SessionInterface
{
    /**
     * set
     *
     * @param  string $property
     * @param  mixed $value
     * @return void
     */
    public function set(string $property, $value): void;
    /**
     * csrfToken
     *
     * @param  string $name
     * @param  bool $generate
     * @return string
     */
    public function csrfToken(string $name, bool $generate = true): string;
    /**
     * validateCsrfToken
     *
     * @param  string $name
     * @param  ?string $token
     * @return bool
     */
    public function validateCsrfToken(string $name, ?string $token): bool;    
    /**
     * captcha
     *
     * @param  string $formName
     * @param  int $length
     * @param  bool $generate
     * @return string
     */
    public function captcha(string $formName, int $length = 6, bool $generate = true): string;
    /**
     * get
     *
     * @param  ?string $property
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function get(?string $property = null, $defaultValue = null);
    /**
     * validateCaptcha
     *
     * @param  string $formName
     * @param  ?string $captcha
     * @return bool
     */
    public function validateCaptcha(string $formName, ?string $captcha): bool;
    /**
     * has
     *
     * @param  ?string $property
     * @return bool
     */
    public function has(?string $property = null): bool;

    /**
     * unset
     *
     * @param  ?string $property
     * @return mixed
     */
    public function unset(?string $property = null);

    /**
     * addFlashInfo
     *
     * @param  string $message
     * @return void
     */
    public function addFlashInfo(string $message): void;

    /**
     * flashInfo
     *
     * @return ?FlashMessage
     */
    public function flashInfo(): ?FlashMessage;
    /**
     * addFlashError
     *
     * @param  string $message
     * @return void
     */
    public function addFlashError(string $message): void;
    /**
     * flashError
     *
     * @return ?FlashMessage
     */
    public function flashError(): ?FlashMessage;
    /**
     * addFlashWarning
     *
     * @param  string $message
     * @return void
     */
    public function addFlashWarning(string $message): void;
    /**
     * flashWarning
     *
     * @return ?FlashMessage
     */
    public function flashWarning(): ?FlashMessage;
    /**
     * addFlashSuccess
     *
     * @param  string $message
     * @return void
     */
    public function addFlashSuccess(string $message): void;
    /**
     * flashSuccess
     *
     * @return ?FlashMessage
     */
    public function flashSuccess(): ?FlashMessage;

    /**
     * addFlash
     *
     * @param  string $type
     * @param  string $message
     * @return void
     */
    public function addFlash(string $type, string $message): void;

    /**
     * flash
     *
     * @param  ?string $type
     * @return FlashMessage|array|null
     */
    public function flash(?string $type = null);

    /**
     * hasFlash
     *
     * @param  ?string $type
     * @return bool
     */
    public function hasFlash(?string $type = null): bool;

    /**
     * hasFlashSuccess
     *
     * @return bool
     */
    public function hasFlashSuccess(): bool;
    /**
     * hasFlashError
     *
     * @return bool
     */
    public function hasFlashError(): bool;
    /**
     * hasFlashWarning
     *
     * @return bool
     */
    public function hasFlashWarning(): bool;
    /**
     * hasFlashInfo
     *
     * @return bool
     */
    public function hasFlashInfo(): bool;
}
