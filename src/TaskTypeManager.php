<?php

namespace Drupal\farm_task;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\entity\BundlePlugin\BundlePluginInterface;
use Drupal\farm_task\Annotation\TaskType;

/**
 * Manages discovery and instantiation of task type plugins.
 *
 * @see \Drupal\farm_entity\Annotation\AssetType
 * @see plugin_api
 */
class TaskTypeManager extends DefaultPluginManager {

  /**
   * Constructs a new TaskTypeManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Task/TaskType', $namespaces, $module_handler, BundlePluginInterface::class, TaskType::class);
    $this->alterInfo('task_type_info');
    $this->setCacheBackend($cache_backend, 'task_type_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);
    foreach (['id', 'label'] as $required_property) {
      if (empty($definition[$required_property])) {
        throw new PluginException(sprintf('The task type %s must define the %s property.', $plugin_id, $required_property));
      }
    }
  }

}
