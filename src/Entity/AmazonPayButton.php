<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Entity;

class AmazonPayButton implements AmazonPayButtonInterface
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $buttonType;

    /** @var string */
    protected $buttonColor;

    /** @var string */
    protected $buttonSize;

    /** @var string */
    protected $buttonLanguage;

    /**
     * @param string $id
     * @param string $buttonType
     * @param string $buttonColor
     * @param string $buttonSize
     * @param string $buttonLanguage
     */
    public function __construct(string $id, string $buttonType, string $buttonColor, string $buttonSize, string $buttonLanguage)
    {
        $this->id = $id;
        $this->buttonType = $buttonType;
        $this->buttonColor = $buttonColor;
        $this->buttonSize = $buttonSize;
        $this->buttonLanguage = $buttonLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonType(): string
    {
        return $this->buttonType;
    }

    /**
     * {@inheritdoc}
     */
    public function setButtonType(string $buttonType): void
    {
        $this->buttonType = $buttonType;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonColor(): string
    {
        return $this->buttonColor;
    }

    /**
     * {@inheritdoc}
     */
    public function setButtonColor(string $buttonColor): void
    {
        $this->buttonColor = $buttonColor;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonSize(): string
    {
        return $this->buttonSize;
    }

    /**
     * {@inheritdoc}
     */
    public function setButtonSize(string $buttonSize): void
    {
        $this->buttonSize = $buttonSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonLanguage(): string
    {
        return $this->buttonLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function setButtonLanguage(string $buttonLanguage): void
    {
        $this->buttonLanguage = $buttonLanguage;
    }


}
