<?php


namespace Hero\DisableEditUsers\Model\Config\Source;

use Magento\Customer\Api\Data\GroupSearchResultsInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Module\Manager as ModuleManager;

class Groups implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param ModuleManager $moduleManager
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ModuleManager $moduleManager,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->moduleManager = $moduleManager;
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->moduleManager->isEnabled('Magento_Customer')) {
            return [];
        }
        $customerGroups = [];
        $customerGroups[] = [
            'label' => __('-- Please Select Groups --'),
            'value' => '',
        ];

        /** @var GroupSearchResultsInterface $groups */
        $groups = $this->groupRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($groups->getItems() as $group) {
            $customerGroups[] = [
                'label' => $group->getCode(),
                'value' => $group->getId(),
            ];
        }

        return $customerGroups;
    }
}
