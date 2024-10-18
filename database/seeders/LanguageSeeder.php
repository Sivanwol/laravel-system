<?

use Illuminate\Database\Seeder;
use DB;
class LanguageSeeder extends Seeder{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'en' => 'English',
            'he' => 'Hebrew',
            'ar' => 'Arabic',
            'am' => 'Amharic',
            'ru' => 'Russian',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'nl' => 'Dutch',
            'pt' => 'Portuguese',
            'tr' => 'Turkish',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'zh' => 'Chinese',
        ];
        foreach ($languages as $code => $name) {
            DB::table('languages')->insert([
                'name' => $name,
                'code' => $code,
            ]);
        }
    }
}
