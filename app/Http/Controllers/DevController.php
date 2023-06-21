<?php
// phpcs:ignoreFile

namespace App\Http\Controllers;

use App\Exceptions\EntityValidationException;
use Illuminate\Http\Request;
use Keywords\Domain\Models\SearchKeyword;
use Pricelists\Domain\Models\ParseRule;
use Pricelists\Domain\Models\ParseTest;
use Pricelists\Domain\Services\ParseRuleService;
use Pricelists\Domain\Services\ParseTestService;
use Products\Contracts\DataTransferObjects\ProductTypeDto;
use Products\Domain\Models\ProductProp;
use Products\Domain\Models\ProductType;
use Products\Domain\Models\ProductType as ProductTypeModel;
use Products\Domain\Services\ProductPropService;
use Products\Domain\Services\ProductTypeService;

class DevController extends Controller
{
    public function index(Request $request, string $action = null)
    {
        if ($action === null) {
            $result = '<p>Available actions:</p><ul>';
            foreach (array_diff(get_class_methods($this), get_class_methods(Controller::class)) as $method) {
                if ($method !== 'index') {
                    $result .= '<li><a href="/dev/' . $method . '">' . $method . '</a></li>';
                }
            }

            return $result . '</ul>';
        }

        if (method_exists($this, $action)) {
            return $this->{$action}($request);
        }

        return null;
    }

    public function __construct()
    {
    }

    public function testService()
    {
    }


    public function setkw(Request $request)
    {
        SearchKeyword::setWords(1, 'product_type', 1, ['шины', 'автошины', 'резина']);
    }

    public function sendPriсelistParseRequest()
    {
        $message = [
            "id" => time(),
            "url" => "https://www.best-tyres.ru/api/export/vsekolesa-mow.xml",
            "categories_product_types" => [
                "pricelist" => ["disabled"],
                "2" => ["tire"],
                "3" => ["rim"],
                "4" => ["tire"],
                "5" => ["tire"]
            ],
            "format" => "xml",
            "skip_if_date_is" => "2022-11-22 22:22:22"
        ];
        /*$message = [
            "id" => time(),
            "url" => "http://export.admitad.com/ru/webmaster/websites/1739949/products/export_adv_products/?user=vsekolesa&code=knbuwa0q2j&feed_id=18915&format=xml",
            "categories_product_types" => [
                '1' => ['akb']
            ],
            "format" => "xml",
            "skip_if_date_is"=> "2022-11-22 22:22:22"
        ];*/
        /*$message = [
            "id" => time(),
            "url" => "https://bshina.ru/market3.xml",
            "categories_product_types" => [
                "1" => ["tire"],
                "2" => ["rim"],
                "3" => ["mtire"],
                "4" => ["ttire"],
                "5" => ["ttire"]
            ],
            "format" => "xml",
            "skip_if_date_is"=> "2022-11-22 22:22:22"
        ];
        $message = [
            "id" => time(),
            "url" => "https://moscow.elitewheels.ru/disks-yml.xml",
            "categories_product_types" => [
                7 => ['rim']
            ],
            "format" => "xml",
            "skip_if_date_is"=> "2022-11-22 22:22:22"
        ];*/

        /*$message = [
            "id" => time(),
            "url" => "https://brandwheels.ru/bitrix/catalog_export/yandex.php",
            "categories_product_types" => array (
                1506 => ['rim'],
                1775 => ['rim'],
                1976 => ['rim'],
                4460 => ['rim'],
                4481 => ['rim'],
                4536 => ['rim'],
                4567 => ['rim'],
                4690 => ['rim'],
                4782 => ['rim'],
                6002 => ['tire'],
                6019 => ['tire'],
                6024 => ['rim'],
                6031 => ['rim'],
                6282 => ['tire'],
                6790 => ['tire'],
                6822 => ['tire'],
                6840 => ['tire'],
                6866 => ['tire'],
                6886 => ['tire'],
                6917 => ['tire'],
                6974 => ['tire'],
                7008 => ['tire'],
                7333 => ['tire'],
                7397 => ['tire'],
                7554 => ['tire'],
                7647 => ['tire'],
                7687 => ['tire'],
                15087 => ['rim'],
                15214 => ['rim'],
            ),
            "format" => "xml",
            "skip_if_date_is"=> "2022-11-22 22:22:22"
        ];*/

       /* $message = [
            'id' => time(),
            'url' => 'https://www.master-shina.ru/bitrix/catalog_export/tires.php',
            "format" => "xml",
            "categories_product_types" => [
                'pricelist' => ['tire']
            ]
        ];

        $message = [
            'id' => time(),
            'url' => 'https://www.shintyre.ru/bitrix/catalog_export/export_pdG1.xml',
            "format" => "xml",
            "categories_product_types" => [
                'pricelist' => ['tire']
            ]
        ];*/

        dd('Sent', $message);
    }
}
