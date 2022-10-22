<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ImportData',
    description: 'Import category from json file',
)]
class ImportDataCommand extends Command
{
    private $entityManager;
    
    public function __construct(ManagerRegistry  $entityManager)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $emCategory = $this->entityManager->getRepository(Category::class);
        $contentCategory = file_get_contents("categories.json");
        $data = json_decode($contentCategory);
        foreach($data as $key=>$item){
            //Min title text is 3 and max title text is 12
            if(strlen($item->title) > 2 && strlen($item->title) < 13){
                $checkCatObj = $emCategory->findOneBy(['title' => $item->title]);
                if($checkCatObj){
                    $io->writeln("Category {{$item->title}} have been already existed");
                }
                else{
                    $cat = new Category();
                    $cat->setTitle($item->title);
                    $cat->setEId($item->eId);
                    $emCategory->save($cat,true);
                    $io->writeln("New Record have been added for category  {{$item->title}}");
                }
            }else{
                $io->writeln("Category {{$item->title}} cannot be created. Min text is 3 and max text is 12");
            }
        }

        $emProduct = $this->entityManager->getRepository(Product::class);
        $contentProduct = file_get_contents("products.json");
        $dataProduct = json_decode($contentProduct);
        foreach($dataProduct as $key=>$item){
            //Min title text is 3 and max title text is 12, min Price is 0 and max Price is 200
            if((strlen($item->title) > 2 && strlen($item->title) < 13) && ($item->price>0 && $item->price <= 200)){
                $checkProductObj = $emProduct->findOneBy(['title' => $item->title]);
                if($checkProductObj){
                    $io->writeln("Category {{$item->title}} have been already existed");
                }
                else{
                    $product = new Product();
                    $product->setTitle($item->title);
                    $product->setPrice($item->price);
                    $product->setEId($item->eId);

                    foreach($item->categoryEId as $keyEId=>$itemEId){
                        
                        $cat = $emCategory->findOneBy(['eId' => $itemEId]);
                        if($cat){
                            $product->addCategory($cat);
                        }
                    }
                    $emProduct->save($product,true);
                    $io->writeln("New Product have been added");
                }
            }else{
                $io->writeln("Category {{$item->title}} cannot be created. Min text is 3 and max text is 12, min Price is 0 and max Price is 200");
            }
        }


        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
