<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Entity;

interface AmazonPayButtonInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getButtonType(): string;

    /**
     * @param string $buttonType
     */
    public function setButtonType(string $buttonType): void;

    /**
     * @return string
     */
    public function getButtonColor(): string;

    /**
     * @param string $buttonColor
     */
    public function setButtonColor(string $buttonColor): void;

    /**
     * @return string
     */
    public function getButtonSize(): string;

    /**
     * @param string $buttonSize
     */
    public function setButtonSize(string $buttonSize): void;

    /**
     * @return string
     */
    public function getButtonLanguage(): string;

    /**
     * @param string $buttonLanguage
     */
    public function setButtonLanguage(string $buttonLanguage): void;


}
