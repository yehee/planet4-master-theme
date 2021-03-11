<?php 


declare(strict_types=1);

/**
 * Give alias with the pre PSR-4 name to all classes.
 *
 * @package P4MT
 * @todo: remove after full namespace deployment to plugins and child themes
 */

use P4\MasterTheme\Activator;
use P4\MasterTheme\AnalyticsValues;
use P4\MasterTheme\Campaigner;
use P4\MasterTheme\CampaignExporter;
use P4\MasterTheme\CampaignImporter;
use P4\MasterTheme\Campaigns;
use P4\MasterTheme\Context;
use P4\MasterTheme\ControlPanel;
use P4\MasterTheme\Cookies;
use P4\MasterTheme\CustomTaxonomy;
use P4\MasterTheme\DevReport;
use P4\MasterTheme\ElasticSearch;
use P4\MasterTheme\ImageCompression;
use P4\MasterTheme\Loader;
use P4\MasterTheme\MetaboxRegister;
use P4\MasterTheme\Post;
use P4\MasterTheme\PostArchive;
use P4\MasterTheme\PostCampaign;
use P4\MasterTheme\PostReportController;
use P4\MasterTheme\Search;
use P4\MasterTheme\Settings;
use P4\MasterTheme\Sitemap;
use P4\MasterTheme\Smartsheet;
use P4\MasterTheme\SmartsheetClient;
use P4\MasterTheme\TaxonomyCampaign;
use P4\MasterTheme\User;

class_alias(Activator::class, 'P4_Activator');
class_alias(AnalyticsValues::class, 'P4_Analytics_Values');
class_alias(CampaignExporter::class, 'P4_Campaign_Exporter');
class_alias(CampaignImporter::class, 'P4_Campaign_Importer');
class_alias(Campaigner::class, 'P4_Campaigner');
class_alias(Campaigns::class, 'P4_Campaigns');
class_alias(Context::class, 'P4_Context');
class_alias(ControlPanel::class, 'P4_Control_Panel');
class_alias(Cookies::class, 'P4_Cookies');
class_alias(CustomTaxonomy::class, 'P4_Custom_Taxonomy');
class_alias(DevReport::class, 'P4_Dev_Report');
class_alias(ElasticSearch::class, 'P4_ElasticSearch');
class_alias(ImageCompression::class, 'P4_Image_Compression');
class_alias(Loader::class, 'P4_Loader');
class_alias(MetaboxRegister::class, 'P4_Metabox_Register');
class_alias(Post::class, 'P4_Post');
class_alias(PostArchive::class, 'P4_Post_Archive');
class_alias(PostCampaign::class, 'P4_Post_Campaign');
class_alias(PostReportController::class, 'P4_Post_Report_Controller');
class_alias(Search::class, 'P4_Search');
class_alias(Settings::class, 'P4_Settings');
class_alias(Sitemap::class, 'P4_Sitemap');
class_alias(Smartsheet::class, 'P4_Smartsheet');
class_alias(SmartsheetClient::class, 'P4_Smartsheet_Client');
class_alias(TaxonomyCampaign::class, 'P4_Taxonomy_Campaign');
class_alias(User::class, 'P4_User');
