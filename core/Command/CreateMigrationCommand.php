<?php
namespace Core\Command;

use Core\Http\Service\Container;
use Dotenv\Dotenv;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Database\Capsule\Manager as DB;
use Core\Database\Database;

#[AsCommand(
    name: 'migration:create',
    hidden: false,
)]
class CreateMigrationCommand extends \Core\Command
{
    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('file');

        $filePath = dirname(__DIR__, 2) . '/App/Migration/' . $fileName . '.php';
        $content = sprintf('<?php
namespace application\migrations;

use Core\Database\Migration\Migration;
use Core\Database\Migration\MigrationInterface;

class %s extends Migration implements MigrationInterface
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {

    }
}',$fileName);

        $req = Container::get()->file->createAndWriteFile($filePath, $content);

        if ($req)
        {
            $req = DB::table('migrations')->insert([
                'file' => $fileName,
                'class' => '/App/Migration/'.$fileName
            ]);
            $io->success('Creating successful');
        } else {
            $io->error('Creating unsuccessful');
        }

        return COMMAND::SUCCESS;
    }
}