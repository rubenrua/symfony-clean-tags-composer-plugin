<?php

namespace Rubenrua\SymfonyCleanTagsComposerPlugin;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\RepositoryFactory;
use Composer\Repository\RepositoryManager;

class Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        if (class_exists('Symfony\Flex\Flex')) {
            if ($io->isDebug()) {
                $io->writeError('symfony/flex is active: Skip the activation');
            }

            return;
        }

        $symfonyRequire = null;

        if (getenv('SYMFONY_REQUIRE')) {
            $symfonyRequire = getenv('SYMFONY_REQUIRE');
        }
        $extra = $composer->getPackage()->getExtra();
        if (isset($extra['symfony']['require'])) {
            $symfonyRequire = $composer->getPackage()->getExtra()['symfony']['require'];
        }

        if ($symfonyRequire) {
            $config = $composer->getConfig();
            $config->merge(array('config' => array('symfony_require' => $symfonyRequire)));

            $manager = $composer->getRepositoryManager();
            $setRepositories = \Closure::bind(function (RepositoryManager $manager) use ($symfonyRequire) {
                $manager->repositoryClasses = $this->repositoryClasses;
                $manager->setRepositoryClass('composer', TruncatedComposerRepository::class);
                $manager->repositories = $this->repositories;
                $i = 0;
                foreach (RepositoryFactory::defaultRepos(null, $this->config, $manager) as $repo) {
                    $manager->repositories[$i++] = $repo;
                    if ($repo instanceof TruncatedComposerRepository && $symfonyRequire) {
                        $repo->setSymfonyRequire($symfonyRequire, $this->io);
                    }
                }
                $manager->setLocalRepository($this->getLocalRepository());
            }, $composer->getRepositoryManager(), RepositoryManager::class);

            $setRepositories($manager);
            $composer->setRepositoryManager($manager);
        }
    }
}
