<?php

namespace Opium\OpiumBundle\Command;

use Opium\OpiumBundle\Entity\Directory;
use Opium\OpiumBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('opium:populate')
            ->setDescription('Populate database from files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.opium_entity_manager');
        $repo = $this->getContainer()->get('opium.repository.file');
        $dirRepo = $this->getContainer()->get('opium.repository.directory');
        $photoRepo = $this->getContainer()->get('opium.repository.photo');

        $root = new Directory();
        $root->setName('');
        if (!$repo->findOneByName('')) {
            $em->persist($root);
        }

        $fileList = $this->getContainer()->get('opium.finder.photo')->find();

        foreach ($fileList as $file) {
            if (!$repo->findOneByName($file->getName())) {
                $em->persist($file);
            }
        }

        $em->flush();

        var_dump('update parent');

        // update parents
        $dirList = $dirRepo->findAll();
        foreach ($dirList as $dir) {
            $photo = $photoRepo->findOneByParent($dir);
            if ($photo) {
                $dir->setDirectoryThumbnail($photo);
            }
        }

        $em->flush();

    }

    private function getParentPath(File $file)
    {
        $path = $file->getName();
        if (strlen($path) < 2) {
            return '';
        }

        $parentPath = substr($path, 0, strrpos($path, '/', -2));

        return $parentPath;
    }
}
