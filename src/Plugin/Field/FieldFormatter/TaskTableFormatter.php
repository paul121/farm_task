<?php

namespace Drupal\farm_task\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\entity_reference_revisions\Plugin\Field\FieldFormatter\EntityReferenceRevisionsFormatterBase;
use Drupal\farm_task\Entity\TaskInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of task table formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_revisions_task_table",
 *   label = @Translation("Task Table"),
 *   description = @Translation("Display the referenced tasks in a table."),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class TaskTableFormatter extends EntityReferenceRevisionsFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Constructs a StringFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, LoggerChannelFactoryInterface $logger_factory, EntityDisplayRepositoryInterface $entity_display_repository) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->loggerFactory = $logger_factory;
    $this->entityDisplayRepository = $entity_display_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('logger.factory'),
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'view_mode' => 'default',
      'link' => FALSE,
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements['view_mode'] = array(
      '#type' => 'select',
      '#options' => $this->entityDisplayRepository->getViewModeOptions($this->getFieldSetting('target_type')),
      '#title' => $this->t('View mode'),
      '#default_value' => $this->getSetting('view_mode'),
      '#required' => TRUE,
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $view_modes = $this->entityDisplayRepository->getViewModeOptions($this->getFieldSetting('target_type'));
    $view_mode = $this->getSetting('view_mode');
    $summary[] = $this->t('Rendered as @mode', array('@mode' => isset($view_modes[$view_mode]) ? $view_modes[$view_mode] : $view_mode));

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $view_mode = $this->getSetting('view_mode');
    $elements = array();

    $task_list = $items->getEntity();

    $count = $items->count() ?? 1;
    $completed_items = array_filter($items->referencedEntities(), function (TaskInterface $task) {
      return $task->completed();
    });

    $table = [
      '#type' => 'table',
      '#caption' => [
        '#theme' => 'progress_bar',
        '#label' => $task_list->label(),
        '#message' => "$count tasks",
        '#percent' => round((count($completed_items)/$count) * 100),
      ],
      '#header' => [
        'Completed',
        'Task',
        'Value',
        'Actions',
      ],
    ];

    /**
     * @var int $delta
     * @var \Drupal\farm_task\Entity\TaskInterface $entity
     */
    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {

      $table[$delta]['complete'] = [
        '#type' => 'checkbox',
        '#default_value' => 1,
        '#attributes' => [
          'checked' => (bool) $entity->completed(),
          'disabled' => TRUE,
        ],
      ];
      $table[$delta]['task'] = [
        '#markup' => $entity->label(),
      ];

      $table[$delta]['value'] = [
        $entity->get($entity->getTargetFieldName())->view(['label' => 'visually_hidden']),
      ];

      $table[$delta]['actions'] = [
        '#type' => 'dropbutton',
        '#dropbutton_type' => 'extrasmall',
        '#links' => [
          'complete' => [
            'title' => $this->t('Complete'),
            'url' => $entity->toUrl(),
          ],
          'edit' => [
            'title' => $this->t('Edit'),
            'url' => $entity->toUrl('edit-form'),
          ],
          'reset' => [
            'title' => $this->t('Reset'),
            'url' => $entity->toUrl('edit-form'),
          ],
        ],
      ];
    }

    $elements = [];
    $elements[0] = $table;
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'task';
  }

}
