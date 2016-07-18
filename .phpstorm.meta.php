<?php
	namespace PHPSTORM_META {
	/** @noinspection PhpUnusedLocalVariableInspection */
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	$STATIC_METHOD_TYPES = [

		\D('') => [
			'Mongo' instanceof Think\Model\MongoModel,
			'View' instanceof Think\Model\ViewModel,
			'Channel' instanceof Home\Model\ChannelModel,
			'Action' instanceof Admin\Model\ActionModel,
			'Config' instanceof Admin\Model\ConfigModel,
			'AuthRule' instanceof Admin\Model\AuthRuleModel,
			'Member' instanceof Home\Model\MemberModel,
			'Digg' instanceof Addons\Digg\Model\DiggModel,
			'Document' instanceof Home\Model\DocumentModel,
			'Hooks' instanceof Admin\Model\HooksModel,
			'Addons' instanceof Admin\Model\AddonsModel,
			'Menu' instanceof Admin\Model\MenuModel,
			'Adv' instanceof Think\Model\AdvModel,
			'Attachment' instanceof Addons\Attachment\Model\AttachmentModel,
			'Category' instanceof Home\Model\CategoryModel,
			'Relation' instanceof Think\Model\RelationModel,
			'Attribute' instanceof Admin\Model\AttributeModel,
			'File' instanceof Home\Model\FileModel,
			'Tree' instanceof Common\Model\TreeModel,
			'Picture' instanceof Admin\Model\PictureModel,
			'Model' instanceof Admin\Model\ModelModel,
			'Url' instanceof Admin\Model\UrlModel,
			'UcenterMember' instanceof User\Model\UcenterMemberModel,
			'AuthGroup' instanceof Admin\Model\AuthGroupModel,
		],
		\DL('') => [
			'DownloadLogic' instanceof Home\Logic\DownloadLogic,
			'ArticleLogic' instanceof Home\Logic\ArticleLogic,
			'BaseLogic' instanceof Home\Logic\BaseLogic,
		],
	];
}