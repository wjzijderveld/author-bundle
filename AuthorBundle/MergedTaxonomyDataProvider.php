<?php

namespace Qandidate\AuthorBundle;

use Sculpin\Core\DataProvider\DataProviderInterface;
use Sculpin\Core\DataProvider\DataProviderManager;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Contrib\ProxySourceCollection\ProxySourceCollectionDataProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MergedTaxonomyDataProvider implements DataProviderInterface, EventSubscriberInterface
{
    private $mergedTaxons = array();
    private $dataProviderManager;
    private $dataProviderName;
    private $taxonomyKey;
    private $mergeDataProvider;
    private $mergeDataKey;
    private $reverseMergeDataKey;

    public function __construct(
        DataProviderManager $dataProviderManager,
        $dataProviderName,
        $taxonomyKey,
        ProxySourceCollectionDataProvider $mergeDataProvider,
        $mergeDataKey
    ) {
        $this->dataProviderManager = $dataProviderManager;
        $this->dataProviderName = $dataProviderName;
        $this->taxonomyKey = $taxonomyKey;
        $this->mergedDataProvider = $mergeDataProvider;
        $this->mergedDataKey = $mergeDataKey;
    }

    public function provideData()
    {
        return $this->mergedTaxons;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_BEFORE_RUN => array('beforeRun', -10),
        );
    }

    public function beforeRun(SourceSetEvent $sourceSetEvent)
    {
        $mergedTaxons = array();
        $dataProvider = $this->dataProviderManager->dataProvider($this->dataProviderName);

        $mergeData = array();
        foreach ($this->mergedDataProvider->provideData() as $data) {
            $mergeData[$data->data()->get($this->mergedDataKey)] = $data;
        }

        $updatedSourceIds = array_map(function($elem) { return $elem->sourceId(); }, $sourceSetEvent->updatedSources());

        foreach ($dataProvider->provideData() as $sourceId => $item) {
            // only updated sources
            if ( ! in_array($sourceId, $updatedSourceIds)) {
                continue;
            }

            if ($itemTaxons = $item->data()->get($this->taxonomyKey)) {
                $normalizedItemTaxons = array();

                foreach ((array) $itemTaxons as $itemTaxon) {
                    $normalizedItemTaxon = trim($itemTaxon);
                    $mergedTaxons[$normalizedItemTaxon][] = $item;

                    if (!isset($mergeData[$normalizedItemTaxon])) {
                        throw new \Exception($normalizedItemTaxon . ' not in ' . implode(' ', array_keys($mergeData)));
                    }

                    $normalizedItemTaxons[] = $mergeData[$normalizedItemTaxon];
                }

                $item->data()->set($this->taxonomyKey, $normalizedItemTaxons);
            }
        }

        foreach ($mergedTaxons as $mergedDataKey => $items) {
            $mergeData[$mergedDataKey]->data()->set($this->dataProviderName, $items);
        }

        $this->mergedTaxons = $mergedTaxons;
    }
}
