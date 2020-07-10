<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\Pro;
use App\Repository\ProRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\String\u;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:add-user
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php bin/console app:add-user -vv
 *
 * See https://symfony.com/doc/current/console.html
 *
 * We use the default services.yaml configuration, so command classes are registered as services.
 * See https://symfony.com/doc/current/console/commands_as_services.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class AddUserCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:add-user';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $pro;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Validator $validator, ProRepository $pro)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->pro = $pro;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('prenom', InputArgument::OPTIONAL, 'The prenom of the new user')
            ->addArgument('nom', InputArgument::OPTIONAL, 'The nom of the new user')
            ->addArgument('numTelephone', InputArgument::OPTIONAL, 'The Telephone Number of the new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('email') && null !== $input->getArgument('password') && null !== $input->getArgument('prenom')
            && null !== $input->getArgument('nom') && null !== $input->getArgument('numTelephone')) {
            return;
        }

        $this->io->title('Ajout d\'un nouvel Utilisateur par le terminal');
        $this->io->text([
            'Si vous préférez ne pas utiliser cet assistant interactif, indiquez les arguments requis par cette commande comme suit:

',
            ' $ php bin/console app:add-user username password email@example.com prenom nom telephoneNumber',
            '',
            'Maintenant, veuillez saisir les arguments non renseigné en suivant les directives.',
        ]);
        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }
        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: '.u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }

        // Ask for the prenom if it's not defined
        $prenom = $input->getArgument('prenom');
        if (null !== $prenom) {
            $this->io->text(' > <info>prenom</info>: '.$prenom);
        } else {
            $prenom = $this->io->ask('prenom', null, [$this->validator, 'validateprenom']);
            $input->setArgument('prenom', $prenom);
        }

        // Ask for the nom if it's not defined
        $nom = $input->getArgument('nom');
        if (null !== $nom) {
            $this->io->text(' > <info>nom</info>: '.$nom);
        } else {
            $nom = $this->io->ask('nom', null, [$this->validator, 'validatenom']);
            $input->setArgument('nom', $nom);
        }

        // Ask for the email if it's not defined
        $numTelephone = $input->getArgument('numTelephone');
        if (null !== $numTelephone) {
            $this->io->text(' > <info>Telephone Number</info>: '.$numTelephone);
        } else {
            $numTelephone = $this->io->ask('Telephone Number', null);
            $input->setArgument('numTelephone', $numTelephone);
        }

    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $isAdmin = $input->getOption('admin');
        $prenom = $input->getArgument('prenom');
        $nom = $input->getArgument('nom');
        $numTelephone = $input->getArgument('numTelephone');

        // make sure to validate the user data is correct
        $this->validateUserData($email, $plainPassword, $prenom, $nom, $numTelephone);

        // create the user and encode its password
        $user = new Pro();
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setNumTelephone($numTelephone);

        // See https://symfony.com/doc/current/security.html#c-encoding-passwords
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isAdmin ? 'Administrator user' : 'User', $user->getUsername(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return 0;
    }

    private function validateUserData( $plainPassword, $email, $prenom, $nom, $numTelephone): void
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->pro->findOneBy(['email' => $email]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $email));
        }

        // validate password and email if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validatePrenom($prenom);
        $this->validator->validateNom($nom);
        $this->validator->validateNumTelephone($numTelephone);

    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command creates new users and saves them in the database:
  <info>php %command.full_name%</info> <comment>username password email</comment>
By default the command creates regular users. To create administrator users,
add the <comment>--admin</comment> option:
  <info>php %command.full_name%</info> username password email <comment>--admin</comment>
If you omit any of the three required arguments, the command will ask you to
provide the missing values:
  # command will ask you for the email
  <info>php %command.full_name%</info> <comment>username password</comment>
  # command will ask you for the email and password
  <info>php %command.full_name%</info> <comment>username</comment>
  # command will ask you for all arguments
  <info>php %command.full_name%</info>
HELP;
    }
}