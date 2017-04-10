<?php
namespace DrawMyAttention\XeroLaravel\Providers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class XeroItem extends \XeroPHP\Models\Accounting\Item
{
  public static function getProperties()
  {
       $properties = parent::getProperties();
       $properties['InventoryAssetAccountCode'] = [false, self::PROPERTY_TYPE_STRING, null, false, false];
       return $properties;
   }

   /*
   public function addSalesDetail(Sale $value)
   {
       $this->propertyUpdated('SalesDetails', $value);
       if (!isset($this->_data['SalesDetails'])) {
           $this->_data['SalesDetails'] = new Remote\Collection();
       }
       $this->_data['SalesDetails'] = $value;
       return $this;
   }

   public function addPurchaseDetail(Purchase $value)
    {
        $this->propertyUpdated('PurchaseDetails', $value);
        if (!isset($this->_data['PurchaseDetails'])) {
            $this->_data['PurchaseDetails'] = new Remote\Collection();
        }
        $this->_data['PurchaseDetails'] = $value;
        return $this;
    }
  */
}

class XeroServiceProvider extends ServiceProvider
{
    private $config = 'xero/config.php';

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config.php' => config_path($this->config),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge defaults
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'xero.config'
        );

        // Grab config
        $config = $this->app->config->get('xero.config');

        $this->app->bind('XeroPrivate', function () use ($config) {
            return new \XeroPHP\Application\PrivateApplication($config);
        });

        $this->app->bind('XeroPublic', function () use ($config) {
            return new \XeroPHP\Application\PublicApplication($config);
        });

        $this->app->bind('XeroPartner', function () use ($config) {
            return new \XeroPHP\Application\PartnerApplication($config);
        });

        $this->app->bind('XeroItem', function(){
           return new XeroItem();
         });

        $this->app->bind('XeroInvoice', function(){
           return new \XeroPHP\Models\Accounting\Invoice();
        });

        $this->app->bind('XeroPurchase', function(){
           return new \XeroPHP\Models\Accounting\Item\Purchase();
        });

        $this->app->bind('XeroPurchaseOrder', function(){
           return new \XeroPHP\Models\Accounting\PurchaseOrder();
        });

        $this->app->bind('XeroPurchaseOrderLine', function(){
            return new \XeroPHP\Models\Accounting\PurchaseOrder\LineItem();
        });

        $this->app->bind('XeroPayment', function(){
           return new \XeroPHP\Models\Accounting\Payment();
        });

        $this->app->bind('XeroInvoiceLine', function(){
            return new \XeroPHP\Models\Accounting\Invoice\LineItem();
        });

        $this->app->bind('XeroContact', function(){
            return new \XeroPHP\Models\Accounting\Contact();
        });

        $this->app->bind('XeroAccount', function(){
            return new \XeroPHP\Models\Accounting\Account();
        });

        $this->app->bind('XeroBrandingTheme', function(){
            return new \XeroPHP\Models\Accounting\BrandingTheme();
        });

        $this->app->bind('XeroAttachment', function(){
            return new \XeroPHP\Models\Accounting\Attachment();
        });
    }
}
