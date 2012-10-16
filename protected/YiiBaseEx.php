<?php

class YiiBaseEx extends YiiBase
{

    /**
     * Class autoload loader.
     * This method is provided to be invoked within an __autoload() magic method.
     * @param string $className class name
     * @return boolean whether the class has been loaded successfully
     */
    public static function autoload($className)
    {
        // use include so that the error PHP file may appear
        if (isset(self::$classMap[$className]))
            include(self::$classMap[$className]);
        else if(isset(self::$_coreClasses[$className]))
			include(YII_PATH.self::$_coreClasses[$className]);
        else {
            // include class file relying on include_path
            if (strpos($className, '\\') === false) {  // class without namespace
                if (self::$enableIncludePath === false) {
                    foreach (self::$_includePaths as $path) {
                        $classFile = $path . DIRECTORY_SEPARATOR . $className . '.php';
                        if (is_file($classFile)) {
                            include($classFile);
                            if (YII_DEBUG && basename(realpath($classFile)) !== $className . '.php')
                                throw new CException(Yii::t('yii', 'Class name "{class}" does not match class file "{file}".', array(
                                            '{class}' => $className,
                                            '{file}' => $classFile,
                                        )));
                            break;
                        }
                    }
                } else {
                    @include($className . '.php');
                    if (!(class_exists($className, false) || interface_exists($className, false)) && strpos($className, '_') !== false) {
                        // Try namespaced version of class name
                        $aClassName = explode('_', strtolower($className));
                        $file = array_pop($aClassName);
                        $classFile = implode(DIRECTORY_SEPARATOR, $aClassName) . DIRECTORY_SEPARATOR . ucfirst($file) . '.php';
                        @include($classFile);
                    }
                    if (!(class_exists($className, false) || interface_exists($className, false))) {
                        throw new CException("Entity '$className' is not exist");
                    }
                    
                }
            } else {  // class name with namespace in PHP 5.3
                $namespace = str_replace(array('\\', '_'), '.', ltrim($className, '\\'));
                if (($path = self::getPathOfAlias($namespace)) !== false)
                    include($path . '.php');
                else
                    return false;
            }
        }
    }
    
    /**
	 * @var array class map for core Yii classes.
	 * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
	 * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
	 */
	private static $_coreClasses=array(
		'CApplication' => '/base/CApplication.php',
		'CApplicationComponent' => '/base/CApplicationComponent.php',
		'CBehavior' => '/base/CBehavior.php',
		'CComponent' => '/base/CComponent.php',
		'CErrorEvent' => '/base/CErrorEvent.php',
		'CErrorHandler' => '/base/CErrorHandler.php',
		'CException' => '/base/CException.php',
		'CExceptionEvent' => '/base/CExceptionEvent.php',
		'CHttpException' => '/base/CHttpException.php',
		'CModel' => '/base/CModel.php',
		'CModelBehavior' => '/base/CModelBehavior.php',
		'CModelEvent' => '/base/CModelEvent.php',
		'CModule' => '/base/CModule.php',
		'CSecurityManager' => '/base/CSecurityManager.php',
		'CStatePersister' => '/base/CStatePersister.php',
		'CApcCache' => '/caching/CApcCache.php',
		'CCache' => '/caching/CCache.php',
		'CDbCache' => '/caching/CDbCache.php',
		'CDummyCache' => '/caching/CDummyCache.php',
		'CEAcceleratorCache' => '/caching/CEAcceleratorCache.php',
		'CFileCache' => '/caching/CFileCache.php',
		'CMemCache' => '/caching/CMemCache.php',
		'CWinCache' => '/caching/CWinCache.php',
		'CXCache' => '/caching/CXCache.php',
		'CZendDataCache' => '/caching/CZendDataCache.php',
		'CCacheDependency' => '/caching/dependencies/CCacheDependency.php',
		'CChainedCacheDependency' => '/caching/dependencies/CChainedCacheDependency.php',
		'CDbCacheDependency' => '/caching/dependencies/CDbCacheDependency.php',
		'CDirectoryCacheDependency' => '/caching/dependencies/CDirectoryCacheDependency.php',
		'CExpressionDependency' => '/caching/dependencies/CExpressionDependency.php',
		'CFileCacheDependency' => '/caching/dependencies/CFileCacheDependency.php',
		'CGlobalStateCacheDependency' => '/caching/dependencies/CGlobalStateCacheDependency.php',
		'CAttributeCollection' => '/collections/CAttributeCollection.php',
		'CConfiguration' => '/collections/CConfiguration.php',
		'CList' => '/collections/CList.php',
		'CListIterator' => '/collections/CListIterator.php',
		'CMap' => '/collections/CMap.php',
		'CMapIterator' => '/collections/CMapIterator.php',
		'CQueue' => '/collections/CQueue.php',
		'CQueueIterator' => '/collections/CQueueIterator.php',
		'CStack' => '/collections/CStack.php',
		'CStackIterator' => '/collections/CStackIterator.php',
		'CTypedList' => '/collections/CTypedList.php',
		'CTypedMap' => '/collections/CTypedMap.php',
		'CConsoleApplication' => '/console/CConsoleApplication.php',
		'CConsoleCommand' => '/console/CConsoleCommand.php',
		'CConsoleCommandBehavior' => '/console/CConsoleCommandBehavior.php',
		'CConsoleCommandEvent' => '/console/CConsoleCommandEvent.php',
		'CConsoleCommandRunner' => '/console/CConsoleCommandRunner.php',
		'CHelpCommand' => '/console/CHelpCommand.php',
		'CDbCommand' => '/db/CDbCommand.php',
		'CDbConnection' => '/db/CDbConnection.php',
		'CDbDataReader' => '/db/CDbDataReader.php',
		'CDbException' => '/db/CDbException.php',
		'CDbMigration' => '/db/CDbMigration.php',
		'CDbTransaction' => '/db/CDbTransaction.php',
		'CActiveFinder' => '/db/ar/CActiveFinder.php',
		'CActiveRecord' => '/db/ar/CActiveRecord.php',
		'CActiveRecordBehavior' => '/db/ar/CActiveRecordBehavior.php',
		'CDbColumnSchema' => '/db/schema/CDbColumnSchema.php',
		'CDbCommandBuilder' => '/db/schema/CDbCommandBuilder.php',
		'CDbCriteria' => '/db/schema/CDbCriteria.php',
		'CDbExpression' => '/db/schema/CDbExpression.php',
		'CDbSchema' => '/db/schema/CDbSchema.php',
		'CDbTableSchema' => '/db/schema/CDbTableSchema.php',
		'CMssqlColumnSchema' => '/db/schema/mssql/CMssqlColumnSchema.php',
		'CMssqlCommandBuilder' => '/db/schema/mssql/CMssqlCommandBuilder.php',
		'CMssqlPdoAdapter' => '/db/schema/mssql/CMssqlPdoAdapter.php',
		'CMssqlSchema' => '/db/schema/mssql/CMssqlSchema.php',
		'CMssqlTableSchema' => '/db/schema/mssql/CMssqlTableSchema.php',
		'CMysqlColumnSchema' => '/db/schema/mysql/CMysqlColumnSchema.php',
		'CMysqlSchema' => '/db/schema/mysql/CMysqlSchema.php',
		'CMysqlTableSchema' => '/db/schema/mysql/CMysqlTableSchema.php',
		'COciColumnSchema' => '/db/schema/oci/COciColumnSchema.php',
		'COciCommandBuilder' => '/db/schema/oci/COciCommandBuilder.php',
		'COciSchema' => '/db/schema/oci/COciSchema.php',
		'COciTableSchema' => '/db/schema/oci/COciTableSchema.php',
		'CPgsqlColumnSchema' => '/db/schema/pgsql/CPgsqlColumnSchema.php',
		'CPgsqlSchema' => '/db/schema/pgsql/CPgsqlSchema.php',
		'CPgsqlTableSchema' => '/db/schema/pgsql/CPgsqlTableSchema.php',
		'CSqliteColumnSchema' => '/db/schema/sqlite/CSqliteColumnSchema.php',
		'CSqliteCommandBuilder' => '/db/schema/sqlite/CSqliteCommandBuilder.php',
		'CSqliteSchema' => '/db/schema/sqlite/CSqliteSchema.php',
		'CChoiceFormat' => '/i18n/CChoiceFormat.php',
		'CDateFormatter' => '/i18n/CDateFormatter.php',
		'CDbMessageSource' => '/i18n/CDbMessageSource.php',
		'CGettextMessageSource' => '/i18n/CGettextMessageSource.php',
		'CLocale' => '/i18n/CLocale.php',
		'CMessageSource' => '/i18n/CMessageSource.php',
		'CNumberFormatter' => '/i18n/CNumberFormatter.php',
		'CPhpMessageSource' => '/i18n/CPhpMessageSource.php',
		'CGettextFile' => '/i18n/gettext/CGettextFile.php',
		'CGettextMoFile' => '/i18n/gettext/CGettextMoFile.php',
		'CGettextPoFile' => '/i18n/gettext/CGettextPoFile.php',
		'CDbLogRoute' => '/logging/CDbLogRoute.php',
		'CEmailLogRoute' => '/logging/CEmailLogRoute.php',
		'CFileLogRoute' => '/logging/CFileLogRoute.php',
		'CLogFilter' => '/logging/CLogFilter.php',
		'CLogRoute' => '/logging/CLogRoute.php',
		'CLogRouter' => '/logging/CLogRouter.php',
		'CLogger' => '/logging/CLogger.php',
		'CProfileLogRoute' => '/logging/CProfileLogRoute.php',
		'CWebLogRoute' => '/logging/CWebLogRoute.php',
		'CDateTimeParser' => '/utils/CDateTimeParser.php',
		'CFileHelper' => '/utils/CFileHelper.php',
		'CFormatter' => '/utils/CFormatter.php',
		'CMarkdownParser' => '/utils/CMarkdownParser.php',
		'CPropertyValue' => '/utils/CPropertyValue.php',
		'CTimestamp' => '/utils/CTimestamp.php',
		'CVarDumper' => '/utils/CVarDumper.php',
		'CBooleanValidator' => '/validators/CBooleanValidator.php',
		'CCaptchaValidator' => '/validators/CCaptchaValidator.php',
		'CCompareValidator' => '/validators/CCompareValidator.php',
		'CDateValidator' => '/validators/CDateValidator.php',
		'CDefaultValueValidator' => '/validators/CDefaultValueValidator.php',
		'CEmailValidator' => '/validators/CEmailValidator.php',
		'CExistValidator' => '/validators/CExistValidator.php',
		'CFileValidator' => '/validators/CFileValidator.php',
		'CFilterValidator' => '/validators/CFilterValidator.php',
		'CInlineValidator' => '/validators/CInlineValidator.php',
		'CNumberValidator' => '/validators/CNumberValidator.php',
		'CRangeValidator' => '/validators/CRangeValidator.php',
		'CRegularExpressionValidator' => '/validators/CRegularExpressionValidator.php',
		'CRequiredValidator' => '/validators/CRequiredValidator.php',
		'CSafeValidator' => '/validators/CSafeValidator.php',
		'CStringValidator' => '/validators/CStringValidator.php',
		'CTypeValidator' => '/validators/CTypeValidator.php',
		'CUniqueValidator' => '/validators/CUniqueValidator.php',
		'CUnsafeValidator' => '/validators/CUnsafeValidator.php',
		'CUrlValidator' => '/validators/CUrlValidator.php',
		'CValidator' => '/validators/CValidator.php',
		'CActiveDataProvider' => '/web/CActiveDataProvider.php',
		'CArrayDataProvider' => '/web/CArrayDataProvider.php',
		'CAssetManager' => '/web/CAssetManager.php',
		'CBaseController' => '/web/CBaseController.php',
		'CCacheHttpSession' => '/web/CCacheHttpSession.php',
		'CClientScript' => '/web/CClientScript.php',
		'CController' => '/web/CController.php',
		'CDataProvider' => '/web/CDataProvider.php',
		'CDbHttpSession' => '/web/CDbHttpSession.php',
		'CExtController' => '/web/CExtController.php',
		'CFormModel' => '/web/CFormModel.php',
		'CHttpCookie' => '/web/CHttpCookie.php',
		'CHttpRequest' => '/web/CHttpRequest.php',
		'CHttpSession' => '/web/CHttpSession.php',
		'CHttpSessionIterator' => '/web/CHttpSessionIterator.php',
		'COutputEvent' => '/web/COutputEvent.php',
		'CPagination' => '/web/CPagination.php',
		'CSort' => '/web/CSort.php',
		'CSqlDataProvider' => '/web/CSqlDataProvider.php',
		'CTheme' => '/web/CTheme.php',
		'CThemeManager' => '/web/CThemeManager.php',
		'CUploadedFile' => '/web/CUploadedFile.php',
		'CUrlManager' => '/web/CUrlManager.php',
		'CWebApplication' => '/web/CWebApplication.php',
		'CWebModule' => '/web/CWebModule.php',
		'CWidgetFactory' => '/web/CWidgetFactory.php',
		'CAction' => '/web/actions/CAction.php',
		'CInlineAction' => '/web/actions/CInlineAction.php',
		'CViewAction' => '/web/actions/CViewAction.php',
		'CAccessControlFilter' => '/web/auth/CAccessControlFilter.php',
		'CAuthAssignment' => '/web/auth/CAuthAssignment.php',
		'CAuthItem' => '/web/auth/CAuthItem.php',
		'CAuthManager' => '/web/auth/CAuthManager.php',
		'CBaseUserIdentity' => '/web/auth/CBaseUserIdentity.php',
		'CDbAuthManager' => '/web/auth/CDbAuthManager.php',
		'CPhpAuthManager' => '/web/auth/CPhpAuthManager.php',
		'CUserIdentity' => '/web/auth/CUserIdentity.php',
		'CWebUser' => '/web/auth/CWebUser.php',
		'CFilter' => '/web/filters/CFilter.php',
		'CFilterChain' => '/web/filters/CFilterChain.php',
		'CHttpCacheFilter' => '/web/filters/CHttpCacheFilter.php',
		'CInlineFilter' => '/web/filters/CInlineFilter.php',
		'CForm' => '/web/form/CForm.php',
		'CFormButtonElement' => '/web/form/CFormButtonElement.php',
		'CFormElement' => '/web/form/CFormElement.php',
		'CFormElementCollection' => '/web/form/CFormElementCollection.php',
		'CFormInputElement' => '/web/form/CFormInputElement.php',
		'CFormStringElement' => '/web/form/CFormStringElement.php',
		'CGoogleApi' => '/web/helpers/CGoogleApi.php',
		'CHtml' => '/web/helpers/CHtml.php',
		'CJSON' => '/web/helpers/CJSON.php',
		'CJavaScript' => '/web/helpers/CJavaScript.php',
		'CJavaScriptExpression' => '/web/helpers/CJavaScriptExpression.php',
		'CPradoViewRenderer' => '/web/renderers/CPradoViewRenderer.php',
		'CViewRenderer' => '/web/renderers/CViewRenderer.php',
		'CWebService' => '/web/services/CWebService.php',
		'CWebServiceAction' => '/web/services/CWebServiceAction.php',
		'CWsdlGenerator' => '/web/services/CWsdlGenerator.php',
		'CActiveForm' => '/web/widgets/CActiveForm.php',
		'CAutoComplete' => '/web/widgets/CAutoComplete.php',
		'CClipWidget' => '/web/widgets/CClipWidget.php',
		'CContentDecorator' => '/web/widgets/CContentDecorator.php',
		'CFilterWidget' => '/web/widgets/CFilterWidget.php',
		'CFlexWidget' => '/web/widgets/CFlexWidget.php',
		'CHtmlPurifier' => '/web/widgets/CHtmlPurifier.php',
		'CInputWidget' => '/web/widgets/CInputWidget.php',
		'CMarkdown' => '/web/widgets/CMarkdown.php',
		'CMaskedTextField' => '/web/widgets/CMaskedTextField.php',
		'CMultiFileUpload' => '/web/widgets/CMultiFileUpload.php',
		'COutputCache' => '/web/widgets/COutputCache.php',
		'COutputProcessor' => '/web/widgets/COutputProcessor.php',
		'CStarRating' => '/web/widgets/CStarRating.php',
		'CTabView' => '/web/widgets/CTabView.php',
		'CTextHighlighter' => '/web/widgets/CTextHighlighter.php',
		'CTreeView' => '/web/widgets/CTreeView.php',
		'CWidget' => '/web/widgets/CWidget.php',
		'CCaptcha' => '/web/widgets/captcha/CCaptcha.php',
		'CCaptchaAction' => '/web/widgets/captcha/CCaptchaAction.php',
		'CBasePager' => '/web/widgets/pagers/CBasePager.php',
		'CLinkPager' => '/web/widgets/pagers/CLinkPager.php',
		'CListPager' => '/web/widgets/pagers/CListPager.php',
	);

}