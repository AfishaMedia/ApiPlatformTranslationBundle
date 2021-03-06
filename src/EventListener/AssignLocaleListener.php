<?php

declare(strict_types=1);

namespace Locastic\ApiPlatformTranslationBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Locastic\ApiPlatformTranslationBundle\Model\TranslatableInterface;
use Locastic\ApiPlatformTranslationBundle\Translation\Translator;

/**
 * Class AssignLocaleListener
 *
 * @package Locastic\ApiPlatformTranslationBundle\EventListener
 */
class AssignLocaleListener
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * AssignLocaleListener constructor.
     *
     * @param Translator $translator
     * @param string $defaultLocale
     */
    public function __construct(Translator $translator, string $defaultLocale = 'en')
    {
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->assignLocale($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->assignLocale($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function assignLocale(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof TranslatableInterface) {
            return;
        }

        $localeCode = $this->translator->loadCurrentLocale();

        $object->setCurrentLocale($localeCode);
        $object->setFallbackLocale($this->defaultLocale);
    }
}
