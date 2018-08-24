Install it:
```
$ composer require sylius/elastic-search-plugin
```

Install elastic search server:

```
$ brew install elasticsearch@5.0
```

Run elastic search server:
```
$ elasticsearch

```
Add those bundles to AppKernel.php:

```
 new \ONGR\ElasticsearchBundle\ONGRElasticsearchBundle(),
 new \SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle(),
 new \SimpleBus\SymfonyBridge\SimpleBusEventBusBundle(),
 new \ONGR\FilterManagerBundle\ONGRFilterManagerBundle(),
 new \Sylius\ElasticSearchPlugin\SyliusElasticSearchPlugin(),
 ```
 
Import config file in app/config/config.yml for default filter set configuration:

```
imports:
   - { resource: "@SyliusElasticSearchPlugin/Resources/config/app/config.yml" }
```

For more configuration take a look at ONGRFilterManager

Configure ONGR bundle in app/config/config.yml:

```
ongr_elasticsearch:
    managers:
        default:
            index:
                index_name: sylius
            mappings:
                SyliusElasticSearchPlugin: {}

sylius_elastic_search:
    attribute_whitelist: ['MUG_COLLECTION_CODE', 'MUG_MATERIAL_CODE'] #Only attibutes with these codes will be indexed
```

Import routing file:
```
   sylius_search:
       resource: "@SyliusElasticSearchPlugin/Resources/config/routing.yml"
       
 ```
 
Create/Setup database:

```
$ bin/console ongr:es:index:create
$ bin/console do:da:cr
$ bin/console do:sch:cr
$ bin/console syl:fix:lo
```

If there is a problem with creating elastic search index run those commands:

```
$ bin/console ongr:es:index:drop --force
$ bin/console ongr:es:index:create
```

Example Request/Response:

It's required to pass channel argument to the search. To activate filter you need to pass in parameter (query, request, attribute) requested field see reference in configuration section.

For e.g:

    /shop-api/taxon-products/mugs?channel=WEB_DE&price=2000;3000
It will activate taxon_slug, price_range and channel filter.

Request:

    /shop-api/taxon-products/mugs?channel=WEB_GB
Response:

10. Filtering by attributes:
You need use attributes query parameter which is an associative array where key is the attribute name and value is an array of attribute values. For e.g:
   
$this->client->request('GET', '/shop-api/products', ['attributes' => ['Mug material' => ['Wood']]], [], ['ACCEPT' => 'application/json']);
This filter also aggregates all attribute values and it will group them by attribute name

Aggregation response from this request:

You can combine filters so for example if you want your products to be filtered in specific locale you can add another query parameter
Example request with locale:
```
$this->client->request('GET', '/shop-api/products', ['attributes' => ['Mug material' => ['Wood']], 'locale' => 'en_GB'], [], ['ACCEPT' => 'application/json']);
```
Aggregation response from this request:

Whole response:

11. Sorting
By name ascending:

    /shop-api/products?channel=WEB_DE&sort[name]=asc
By price descending:

    /shop-api/products?channel=WEB_DE&sort[price]=desc
By attribute ATTRIBUTE_CODE ascending:

    /shop-api/products?channel=WEB_DE&sort[attributes][ATTRIBUTE_CODE]=asc
By price ascending, then by name descending:

    /shop-api/products?channel=WEB_DE&sort[price]=asc&sort[name]=desc
Filtering by attribute
By attribute name and value:

    /shop-api/products?channel=WEB_DE&attributes[Attribute name][0]=value
By attribute code and value:

    /shop-api/products?channel=WEB_DE&attributesByCode[ATTRIBUTE_CODE][0]=value
    
Reindexing Elasticsearch
The current implementation does not support updating Elasticsearch when an entity is updated. In order to stay up-to-date, run the following command:

```
    bin/console sylius:elastic-search:update-product-index
```
If you want to recreate the index, run (it will drop it):

```
    bin/console sylius:elastic-search:reset-product-index
```
