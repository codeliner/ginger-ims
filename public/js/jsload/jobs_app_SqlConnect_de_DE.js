
var Targets=$CL.namespace('SqlConnect.View.Targets');$CL.require('Cl.Backbone.View');Targets.Show=function(){};Targets.Show=$CL.extendClass(Targets.Show,Cl.Backbone.View,{events:{'click .sqlconnect-set-target':'onSetTargetClick'},onSetTargetClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a');if($a.hasClass('btn-success')){$a.removeClass('btn-success').find('i').removeClass('icon-ok').addClass('icon-stop');$CL.get("application").router.forward('sqlconnect_target',{action:'remove',id:this.data.id});}else{$a.addClass('btn-success').find('i').removeClass('icon-stop').addClass('icon-ok');$CL.get("application").router.forward('sqlconnect_target',{action:'add',id:this.data.id});}}});var Targets=$CL.namespace('SqlConnect.View.Targets');$CL.require('Cl.Backbone.View');Targets.Index=function(){};Targets.Index=$CL.extendClass(Targets.Index,Cl.Backbone.View,{targetCollection:null,setTargetCollection:function(targetCollection){this.targetCollection=targetCollection;},events:{'change select[name=connection]':'onConnectionChange','click .js-sources .js-change-source':'onChangeSourceClick'},render:function(){this.parent.prototype.render.apply(this);if(this.data.connection){this.$el.find('select[name=connection]').val(this.data.connection).change();}},onConnectionChange:function(e){this.$el.find('.js-sources .ts-tr').remove();var connectionName=$(e.target).val();if(connectionName!='none'){this.targetCollection.setConnection(connectionName);$CL.app().wait();this.targetCollection.reset();this.targetCollection.fetch({success:$CL.bind(function(col){col.each($CL.bind(function(source){var $tr=$('<div />').addClass('ts-tr');var iconClass='icon-plus';if(source.get('is_target')){$tr.addClass('info');iconClass='icon-minus';}
var $td1=$('<div />').addClass('ts-td10').html($('<a />').attr('href','#'+helpers.uri('sqlconnect_target',{connection:connectionName,action:'show',id:source.get('id')})).html(source.get('name')));$tr.append($td1);var $td2=$('<div />').addClass('ts-td2').html($('<a />').attr('href','#').addClass('js-change-source').data('id',source.get('id')).html($('<i />').addClass(iconClass)));$tr.append($td2);this.$el.find('.js-sources').append($tr);},this));$CL.app().stopWait();},this),error:function(col,jqX){$CL.app().stopWait().alert("Failed fetching sources.",jqX);}});}},onChangeSourceClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a'),isTarget=false;if($a.find('i').hasClass('icon-plus')){isTarget=true;}
var source=this.targetCollection.get($a.data('id'));source.save({is_target:isTarget},{success:function(){$a.find('i').toggleClass('icon-plus icon-minus');$a.parents('.ts-tr').toggleClass('info');},error:function(model,jqX){$CL.get("application").alert("Failed saving target.",jqX);}});}});var Sources=$CL.namespace('SqlConnect.View.Sources');$CL.require('Cl.Backbone.View');Sources.Show=function(){};Sources.Show=$CL.extendClass(Sources.Show,Cl.Backbone.View,{events:{'click .sqlconnect-set-source':'onSetSourceClick'},onSetSourceClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a');if($a.hasClass('btn-success')){$a.removeClass('btn-success').find('i').removeClass('icon-ok').addClass('icon-stop');$CL.get("application").router.forward('sqlconnect_source',{action:'remove',id:this.data.id});}else{$a.addClass('btn-success').find('i').removeClass('icon-stop').addClass('icon-ok');$CL.get("application").router.forward('sqlconnect_source',{action:'add',id:this.data.id});}}});var Sources=$CL.namespace('SqlConnect.View.Sources');$CL.require('Cl.Backbone.View');Sources.Index=function(){};Sources.Index=$CL.extendClass(Sources.Index,Cl.Backbone.View,{sourceCollection:null,setSourceCollection:function(sourceCollection){this.sourceCollection=sourceCollection;},events:{'change select[name=connection]':'onConnectionChange','click .js-sources .js-change-source':'onChangeSourceClick'},render:function(){this.parent.prototype.render.apply(this);if(this.data.connection){this.$el.find('select[name=connection]').val(this.data.connection).change();}},onConnectionChange:function(e){this.$el.find('.js-sources .ts-tr').remove();var connectionName=$(e.target).val();if(connectionName!='none'){this.sourceCollection.setConnection(connectionName);$CL.app().wait();this.sourceCollection.reset();this.sourceCollection.fetch({success:$CL.bind(function(col){col.each($CL.bind(function(source){var $tr=$('<div />').addClass('ts-tr');var iconClass='icon-plus';if(source.get('is_source')){$tr.addClass('info');iconClass='icon-minus';}
var $td1=$('<div />').addClass('ts-td10').html($('<a />').attr('href','#'+helpers.uri('sqlconnect_source',{connection:connectionName,action:'show',id:source.get('id')})).html(source.get('name')));$tr.append($td1);var $td2=$('<div />').addClass('ts-td2').html($('<a />').attr('href','#').addClass('js-change-source').data('id',source.get('id')).html($('<i />').addClass(iconClass)));$tr.append($td2);this.$el.find('.js-sources').append($tr);},this));$CL.app().stopWait();},this),error:function(col,jqX){$CL.app().stopWait().alert("Failed fetching sources.",jqX);}});}},onChangeSourceClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a'),isSource=false;if($a.find('i').hasClass('icon-plus')){isSource=true;}
var source=this.sourceCollection.get($a.data('id'));source.save({is_source:isSource},{success:function(){$a.find('i').toggleClass('icon-plus icon-minus');$a.parents('.ts-tr').toggleClass('info');},error:function(model,jqX){$CL.get("application").alert("Failed saving source.",jqX);}});}});var Targets=$CL.namespace('SqlConnect.View.Targets');$CL.require('Cl.Backbone.View');Targets.HelpDialog=function(){};Targets.HelpDialog=$CL.extendClass(Targets.HelpDialog,Cl.Backbone.View,{targetName:null,setTargetName:function(targetName){this.targetName=targetName;},render:function(){this.$el.html($CL.translate('SqlConnect::TARGET::HELP').replace(':targetName',this.targetName));}});var Targets=$CL.namespace('SqlConnect.View.Targets');$CL.require('Cl.Backbone.View');Targets.Options=function(){};Targets.Options=$CL.extendClass(Targets.Options,Cl.Backbone.View,{events:{'click label[for=sqlconnect-target-empty-table]':'onEmptyTableClick'},onEmptyTableClick:function(e){if(e.target.nodeName.toLowerCase()!="input"){var $label=$CL.jTarget(e.target,'label');if($label.find('input').is(':checked')){$label.find('input').removeAttr('checked');}else{$label.find('input').attr('checked','checked');}}},render:function(){this.parent.prototype.render.apply(this);if(!$CL.isEmpty(this.data.options)){if(this.data.options.emptyTable){this.$el.find('input[name=sqlconnect-target-empty-table]').attr('checked','checked');}}}});var Db=$CL.namespace('SqlConnect.Model.Db');$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");$CL.require('SqlConnect.View.Targets.Options');$CL.require('SqlConnect.View.Targets.HelpDialog');Db.TableTarget=function(){this.name=null;this.helpView=null;this.optionsView=null;};Db.TableTarget.prototype={__IMPLEMENTS__:[Ginger.Application.Service.ModuleElement.ElementInterface],setup:function(options){this.name=options.name;},getOptionsView:function(elementData){if(_.isNull(this.optionsView)){this.optionsView=$CL.makeObj('SqlConnect.View.Targets.Options');this.optionsView.setTemplate($CL._template('sqlconnect/targets/options'));}
this.optionsView.setData(elementData);return this.optionsView;},getHelpView:function(elementData){if(_.isNull(this.helpView)){this.helpView=$CL.makeObj('SqlConnect.View.Targets.HelpDialog');}
this.helpView.setTargetName(elementData.name);return this.helpView;},collectOptions:function(){var options={};if($('#js-target-options').find('input[name=sqlconnect-target-empty-table]').is(':checked')){options['emptyTable']=true;}
return options;}}
var Sources=$CL.namespace('SqlConnect.View.Sources');$CL.require('Cl.Backbone.BlockingView');Sources.Options=function(){};Sources.Options=$CL.extendClass(Sources.Options,Cl.Backbone.BlockingView,{render:function(){this.parent.prototype.render.apply(this);if(!$CL.isEmpty(this.data.options)){this.$el.find('select[name=sqlconnect-source-count-column]').val(this.data.options.countColumn);if(this.data.options.customSql){this.$el.find('textarea[name=sqlconnect-source-custom-sql]').val(this.data.options.customSql);}}}});var Sources=$CL.namespace('SqlConnect.View.Sources');$CL.require('Cl.Backbone.View');Sources.HelpDialog=function(){};Sources.HelpDialog=$CL.extendClass(Sources.HelpDialog,Cl.Backbone.View,{sourceName:null,setSourceName:function(sourceName){this.sourceName=sourceName;},render:function(){this.$el.html($CL.translate('SqlConnect::SOURCE::HELP').replace(':sourceName',this.sourceName));}});var Db=$CL.namespace('SqlConnect.Model.Db');$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");$CL.require("SqlConnect.View.Sources.HelpDialog");$CL.require("SqlConnect.View.Sources.Options");Db.TableSource=function(){this.name=null;this.helpDialog=null;this.optionsView=null;this.sourceInfoCollection=null;};Db.TableSource.prototype={__IMPLEMENTS__:[Ginger.Application.Service.ModuleElement.ElementInterface],setup:function(options){this.name=options.name;},setSourceInfoCollection:function(sourceInfoCollection){this.sourceInfoCollection=sourceInfoCollection;},getOptionsView:function(elementData){if(_.isNull(this.optionsView)){this.optionsView=$CL.makeObj('SqlConnect.View.Sources.Options');this.optionsView.setTemplate($CL._template('sqlconnect/sources/options'));}
var sourceInfo=this.sourceInfoCollection.get(elementData.id),sourceInfoData={};if(!sourceInfo){this.optionsView.blockRendering();this.sourceInfoCollection.add({id:elementData.id});sourceInfo=this.sourceInfoCollection.get(elementData.id);sourceInfo.fetch({success:$CL.bind(function(model){this.optionsView.setData(_.extend({sourceInfo:model.toJSON()},elementData));this.optionsView.stopBlocking();},this),error:function(model,jqX){$CL.app().alert('Failed to fetch sourceInfo.',jqX);}});}else{sourceInfoData=sourceInfo.toJSON();}
this.optionsView.setData(_.extend({sourceInfo:sourceInfoData},elementData));return this.optionsView;},getHelpView:function(elementData){if(_.isNull(this.helpDialog)){this.helpDialog=$CL.makeObj('SqlConnect.View.Sources.HelpDialog');}
this.helpDialog.setSourceName(elementData.name);return this.helpDialog;},collectOptions:function(){var options={},countColumn=$('#js-source-options').find('select[name=sqlconnect-source-count-column]').val(),customSql=$('#js-source-options').find('textarea[name=sqlconnect-source-custom-sql]').val();if(!$CL.isEmpty(countColumn)){options['countColumn']=countColumn;}else{options['countColumn']='id';}
if(!$CL.isEmpty(customSql)){options['customSql']=customSql;}
return options;}}
var Entity=$CL.namespace('SqlConnect.Entity');$CL.require('Cl.Backbone.Model');Entity.Connection=function(){};Entity.Connection=$CL.extendClass(Entity.Connection,Cl.Backbone.Model,{idAttribute:"name"});var Collection=$CL.namespace('SqlConnect.Collection');$CL.require("Cl.Backbone.Collection");$CL.require("SqlConnect.Entity.Connection");Collection.Connections=function(){};Collection.Connections=$CL.extendClass(Collection.Connections,Cl.Backbone.Collection,{url:'/sqlconnect/rest/connections',modelClass:'SqlConnect.Entity.Connection',model:SqlConnect.Entity.Connection});var Entity=$CL.namespace('SqlConnect.Entity');$CL.require('Cl.Backbone.Model');Entity.Target=function(){};Entity.Target=$CL.extendClass(Entity.Target,Cl.Backbone.Model);var Collection=$CL.namespace('SqlConnect.Collection');$CL.require("Cl.Backbone.Collection");$CL.require("SqlConnect.Entity.Target");Collection.TargetTables=function(){};Collection.TargetTables=$CL.extendClass(Collection.TargetTables,Cl.Backbone.Collection,{urlBase:'/sqlconnect/rest/targets',url:"",modelClass:'SqlConnect.Entity.Target',model:SqlConnect.Entity.Target,setConnection:function(connection){this.url=this.urlBase+"/"+connection;}});var Entity=$CL.namespace('SqlConnect.Entity');$CL.require('Cl.Backbone.Model');Entity.Source=function(){};Entity.Source=$CL.extendClass(Entity.Source,Cl.Backbone.Model);var Collection=$CL.namespace('SqlConnect.Collection');$CL.require("Cl.Backbone.Collection");$CL.require("SqlConnect.Entity.Source");Collection.SourceTables=function(){};Collection.SourceTables=$CL.extendClass(Collection.SourceTables,Cl.Backbone.Collection,{urlBase:'/sqlconnect/rest/sources',url:"",modelClass:'SqlConnect.Entity.Source',model:SqlConnect.Entity.Source,setConnection:function(connection){this.url=this.urlBase+"/"+connection;}});var Controller=$CL.namespace("SqlConnect.Controller");$CL.require("Cl.Application.Mvc.AbstractController");Controller.Targets=function(){};Controller.Targets=$CL.extendClass(Controller.Targets,Cl.Application.Mvc.AbstractController,{connectionsCollection:null,targetCollection:null,setTargetCollection:function(targetCollection){this.targetCollection=targetCollection;},setConnectionsCollection:function(connectionsCollection){this.connectionsCollection=connectionsCollection;},indexAction:function()
{this.getMvcEvent().stopPropagation();this.addBreadcrumbs();var connection=this.getMvcEvent().getRouteMatch().getParam('connection');this.connectionsCollection.fetch({success:$CL.bind(function(col){var viewData={connection:connection,type:"target"},connections=col.toJSON();connections=_.where(connections,{isTarget:true});viewData.connections=connections;$CL.app().stopWait().continueDispatch(this.getMvcEvent().setResponse(viewData));},this),error:function(col,jqX){$CL.app().stopWait().alert("Failed fetching connections data.",jqX);}});},showAction:function(){this.getMvcEvent().stopPropagation();var targetId=this.getMvcEvent().getRouteMatch().getParam('id'),connection=this.getMvcEvent().getRouteMatch().getParam('connection');this.targetCollection.setConnection(connection);var target=this.targetCollection.get(targetId);$CL.get("application").wait();if(!target){target=$CL.makeObj('SqlConnect.Entity.Target',{id:targetId});this.targetCollection.add(target);}
target.fetch({success:$CL.bind(function(model){this.addBreadcrumbs(model.get('name'),connection);this.getMvcEvent().setResponse(model.toJSON());$CL.get("application").stopWait().continueDispatch(this.getMvcEvent());},this),error:function(){$CL.get("application").alert().stopWait();}});},addBreadcrumbs:function(targetName,connection){var targetsParams={};if($CL.isDefined(connection)){targetsParams.connection=connection;}
var breadcrumbs=[{link:helpers.uri('sqlconnect_index'),label:'SqlConnect'},{link:helpers.uri('sqlconnect_targets',targetsParams),label:$CL.translate('SQLCONNECT::BUTTON::MANAGE_TARGETS')}];if($CL.isDefined(targetName)){breadcrumbs.push({link:'',label:targetName});}
this.getMvcEvent().setParam('breadcrumbs',breadcrumbs);}});var Controller=$CL.namespace("SqlConnect.Controller");$CL.require("Cl.Application.Mvc.AbstractController");Controller.Sources=function(){};Controller.Sources=$CL.extendClass(Controller.Sources,Cl.Application.Mvc.AbstractController,{connectionsCollection:null,sourceCollection:null,setSourceCollection:function(sourceCollection){this.sourceCollection=sourceCollection;},setConnectionsCollection:function(connectionsCollection){this.connectionsCollection=connectionsCollection;},indexAction:function()
{this.getMvcEvent().stopPropagation();this.addBreadcrumbs();var connection=this.getMvcEvent().getRouteMatch().getParam('connection');this.connectionsCollection.fetch({success:$CL.bind(function(col){var viewData={connection:connection,type:"source"},connections=col.toJSON();connections=_.where(connections,{isSource:true});viewData.connections=connections;$CL.app().stopWait().continueDispatch(this.getMvcEvent().setResponse(viewData));},this),error:function(col,jqX){$CL.app().stopWait().alert("Failed fetching connections data.",jqX);}});},showAction:function(){this.getMvcEvent().stopPropagation();var sourceId=this.getMvcEvent().getRouteMatch().getParam('id'),connection=this.getMvcEvent().getRouteMatch().getParam('connection');this.sourceCollection.setConnection(connection);var source=this.sourceCollection.get(sourceId);$CL.get("application").wait();if(!source){source=$CL.makeObj('SqlConnect.Entity.Source',{id:sourceId});this.sourceCollection.add(source);}
source.fetch({success:$CL.bind(function(model){this.addBreadcrumbs(model.get('name'),connection);this.getMvcEvent().setResponse(model.toJSON());$CL.get("application").stopWait().continueDispatch(this.getMvcEvent());},this),error:function(){$CL.get("application").alert().stopWait();}});},addBreadcrumbs:function(sourceName,connection){var sourcesParams={};if($CL.isDefined(connection)){sourcesParams.connection=connection;}
var breadcrumbs=[{link:helpers.uri('sqlconnect_index'),label:'SqlConnect'},{link:helpers.uri('sqlconnect_sources',sourcesParams),label:$CL.translate('SQLCONNECT::BUTTON::MANAGE_SOURCES')}];if($CL.isDefined(sourceName)){breadcrumbs.push({link:'',label:sourceName});}
this.getMvcEvent().setParam('breadcrumbs',breadcrumbs);}});var Index=$CL.namespace('SqlConnect.View.Index');$CL.require('Cl.Backbone.View');$CL.require('Cl.Core.String');Index.ConfigurationEdit=function(){};Index.ConfigurationEdit=$CL.extendClass(Index.ConfigurationEdit,Cl.Backbone.View,{dirverParams:{PDOMySql:{'dbname':'input','charset':'input','host':'input','port':'input','user':'input','password':'input','driverOptions':'textarea'}},events:{'click .js-driver-save .js-btn-save':'onSaveClick','click .js-driver-save .js-btn-cancel':'onCancelClick','change select[name=driverClass]':'onDriverClassChange','click input[name=show-password]':'onShowPasswordClick','click input[name=source]':'onConnectSiteClick','click input[name=target]':'onConnectSiteClick','click label[for=source]':'onConnectSiteLabelClick','click label[for=target]':'onConnectSiteLabelClick'},render:function(){this.parent.prototype.render.apply(this);if(!this.data.isNew){this.$el.find('input[name=name]').val(this.data.name);if(this.data.isSource){this.$el.find('input[name=source]').attr('checked','checked');}
if(this.data.isTarget){this.$el.find('input[name=target]').attr('checked','checked');}
this.$el.find('select[name=driverClass]').val(this.data.params.driverClass).change();var params=$CL.clone(this.data.params);_.each(this.dirverParams[params.driverClass],function(inputType,name){if(inputType=='textarea'){params[name]=JSON.stringify(params[name]).trim('{').trim('}');}
this.$el.find('.'+params.driverClass).find(inputType+'[name='+name+']').val(params[name]);},this);}},onSaveClick:function(e){e.preventDefault();var isSource=this.$el.find('input[name=source]').is(':checked'),isTarget=this.$el.find('input[name=target]').is(':checked'),driverClass=this.$el.find('select[name=driverClass]').val(),connectionName=this.$el.find('input[name=name]').val();if(connectionName==""){this._setError($CL.translate('SQLCONNECT::ERROR::CONNECTION_NAME_EMPTY'));return;}
if(driverClass=="none"){this._setError($CL.translate('SQLCONNECT::ERROR::CHOOSE_DRIVER'));return;}
if(!isSource&&!isTarget){this._setError($CL.translate('SQLCONNECT::ERROR::CHOOSE_CONNECT_SITE'));return;}
var params={};var error=false;_.each(this.dirverParams[driverClass],function(inputType,name){params[name]=this.$el.find('.'+driverClass).find(inputType+'[name='+name+']').val();if(inputType=='textarea'){try{params[name]=JSON.parse('{'+params[name]+'}');}catch(e){error=true;$CL.log(e);this._setError($CL.translate('ERROR::JSON_PARSE'));}}},this);if(error){return;}
params['driverClass']=driverClass;var config={name:connectionName,params:params,isSource:isSource,isTarget:isTarget,isNew:this.data.isNew}
$CL.app().wait();$.post('/sqlconnect/rest/connection/test',{connection:JSON.stringify(params)},$CL.bind(function(response){$CL.app().router.forward('sqlconnect_configuration_save',{connectionConfig:config});},this),'json').fail($CL.bind(function(jqX){this._setError($CL.translate('SQLCONNECT::ERROR::CONNECTION_FAILED')+'<br />'+jqX.responseText);},this)).always(function(){$CL.app().stopWait();});},onCancelClick:function(e){e.preventDefault();$CL.app().router.callRoute('sqlconnect_configuration');},onDriverClassChange:function(e){var $sel=$(e.target);this.$el.find('.js-error-box').addClass('hide');this.$el.find('.js-driver-config').addClass('hide').filter('.'+$sel.val()).removeClass('hide');},onConnectSiteClick:function(e){this.$el.find('.js-error-box').addClass('hide');},onConnectSiteLabelClick:function(e){if(e.target.nodeName.toLowerCase()=='label'){$(e.target).find('input').click();}},onShowPasswordClick:function(e){var $chk=$(e.target);var $passInput=$chk.parents('.js-driver-config').find('input[name=password]');if($chk.is(':checked')){var $text=$('<input />').attr({type:'text',name:'password'}).val($passInput.val()).addClass($passInput.attr('class'));$passInput.replaceWith($text);}else{var $text=$('<input />').attr({type:'password',name:'password'}).val($passInput.val()).addClass($passInput.attr('class'));$passInput.replaceWith($text);}},_setError:function(msg){this.$el.find('.js-error-box').removeClass('hide').find('.alert').html(msg);}});var Index=$CL.namespace('SqlConnect.View.Index');$CL.require('Cl.Backbone.View');Index.Configuration=function(){};Index.Configuration=$CL.extendClass(Index.Configuration,Cl.Backbone.View,{events:{'click .js-add-connection':'onAddConnectionClick','click .js-connections .js-edit':'onEditConnectionClick','click .js-connections .js-remove':'onRemoveConnectionClick'},onAddConnectionClick:function(){$CL.app().router.callRoute('sqlconnect_configuration_add');},onEditConnectionClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a');$CL.app().router.callRoute('sqlconnect_configuration_edit',{connection:$a.data('id')});},onRemoveConnectionClick:function(e){e.preventDefault();var $a=$CL.jTarget(e.target,'a');$CL.app().wait().router.forward('sqlconnect_configuration_remove',{connection:$a.data('id'),callback:function(success){$CL.app().stopWait();if(success){$a.parents('.ts-tr').remove();}}});}});var Index=$CL.namespace('SqlConnect.View.Index');$CL.require('Cl.Backbone.View');Index.Index=function(){};Index.Index=$CL.extendClass(Index.Index,Cl.Backbone.View);var Controller=$CL.namespace("SqlConnect.Controller");$CL.require("Cl.Application.Mvc.AbstractController");$CL.require("SqlConnect.View.Index.Index");$CL.require("SqlConnect.View.Index.Configuration");$CL.require("SqlConnect.View.Index.ConfigurationEdit");Controller.Index=function(){};Controller.Index=$CL.extendClass(Controller.Index,Cl.Application.Mvc.AbstractController,{moduleCollection:null,connectionsCollection:null,setModuleCollection:function(moduleCollection){this.moduleCollection=moduleCollection;},setConnectionsCollection:function(connectionsCollection){this.connectionsCollection=connectionsCollection;},indexAction:function()
{this.getMvcEvent().setParam('breadcrumbs',[{link:helpers.uri('sqlconnect_index'),label:'SqlConnect'}]);var sqlConnectModule=this.moduleCollection.get('SqlConnect'),_checkModuleConfiguration=$CL.bind(function(model){if($CL.isEmpty(model.get("configuration"))){$CL.app().router.callRoute('sqlconnect_configuration');}else{return{};}
return false;},this);if(!sqlConnectModule.get('configuration')||$CL.isEmpty(sqlConnectModule.get('configuration'))){$CL.app().wait();this.getMvcEvent().stopPropagation();sqlConnectModule.fetch({'success':$CL.bind(function(model){$CL.app().stopWait();var response=_checkModuleConfiguration(model);if(response!==false){$CL.app().continueDispatch(this.getMvcEvent().setResponse(response));}},this),'error':function(model,jqX){$CL.app().stopWait().alert('Failed fetching module config. Server Response: '+jqX.responseText);}});return;}
response=_checkModuleConfiguration(sqlConnectModule);if(response===false){this.getMvcEvent().stopPropagation();return;}else{return response;}},configurationAction:function(){this.getMvcEvent().setParam('breadcrumbs',[{link:helpers.uri('sqlconnect_index'),label:'SqlConnect'},{link:'',label:'SqlConnect - '+$CL.translate('GENERAL::CONFIGURATION')}]);var sqlConnectModule=this.moduleCollection.get('SqlConnect'),_processConfiguration=$CL.bind(function(model){return model.get('configuration');},this);if(!sqlConnectModule.get('configuration')||$CL.isEmpty(sqlConnectModule.get('configuration'))){$CL.app().wait();this.getMvcEvent().stopPropagation();sqlConnectModule.fetch({'success':$CL.bind(function(model){$CL.app().stopWait().continueDispatch(this.getMvcEvent().setResponse(_processConfiguration(model)));},this),'error':function(model,jqX){$CL.app().stopWait().alert('Failed fetching module config. Server Response: '+jqX.responseText);}});return;}
return _processConfiguration(sqlConnectModule);},configurationEditAction:function(){this.getMvcEvent().setParam('breadcrumbs',[{link:helpers.uri('sqlconnect_index'),label:'SqlConnect'},{link:helpers.uri('sqlconnect_configuration'),label:'SqlConnect - '+$CL.translate('GENERAL::CONFIGURATION')},{link:'',label:'SqlConnect - '+$CL.translate('SQLCONNECT::DATABASE_CONNECTION_EDIT')}]);var connectionName=this.getMvcEvent().getRouteMatch().getParam('connection'),connection,viewData={},_processConnectionEdit=function(connection){var viewData=connection.toJSON();viewData.isNew=false;return viewData;};if(connectionName){connection=this.connectionsCollection.get(connectionName);if(!connection){var config=this.moduleCollection.get('SqlConnect');if(config&&config.connections){_.each(config.connections,function(conData,conName){if(conName==connectionName){connection=this.connectionsCollection.add({name:conName});connection.set(conData);}});}
if(!connection){$CL.app().wait();this.getMvcEvent().stopPropagation();this.connectionsCollection.fetch({success:$CL.bind(function(collection){$CL.app().stopWait();connection=collection.get(connectionName);if(!connection){$CL.app().alert('Connection ":connection" can not be found.'.replace(':connection',connectionName));}
$CL.app().continueDispatch(this.getMvcEvent().setResponse(_processConnectionEdit(connection)));},this),error:function(col,jqX){$CL.app().stopWait().alert("Failed fetching connections. Server Response: "+jqX.responseText);}});return;}}
return _processConnectionEdit(connection);}else{viewData.isNew=true;}
return viewData;},configurationSaveAction:function(){var config=this.getMvcEvent().getRouteMatch().getParam('connectionConfig',{});if(config.isNew){var connection=$CL.makeObj('SqlConnect.Entity.Connection');this.connectionsCollection.add(connection);delete config.isNew;connection.set(config);$CL.app().wait();connection.sync("create",connection,{success:$CL.bind(function(response){var sqlConnectModule=this.moduleCollection.get('SqlConnect');sqlConnectModule.fetch({success:function(){$CL.app().stopWait().router.callRoute('sqlconnect_configuration');},error:function(model,jqX){$CL.app().stopWait().alert("Failed refreshing SqlConnect configuration. Server Response: "+jqX.responseText);}});},this),error:$CL.bind(function(jqXhr){$CL.app().stopWait().alert("Failed saving connection config. Server Response: "+jqXhr.responseText);},this)});}else{connection=this.connectionsCollection.get(config.name);delete config.isNew;$CL.app().wait();connection.save(config).done($CL.bind(function(){var sqlConnectModule=this.moduleCollection.get('SqlConnect');sqlConnectModule.fetch({success:function(){$CL.app().stopWait().router.callRoute('sqlconnect_configuration');},error:function(model,jqX){$CL.app().stopWait().alert("Failed refreshing SqlConnect configuration. Server Response: "+jqX.responseText);}});},this)).fail(function(jqX){$CL.app().stopWait().alert('Failed saving connection. Server Resposne: '+jqX.responseText);});}
this.getMvcEvent().stopPropagation();return;},configurationRemoveAction:function(){var connectionName=this.getMvcEvent().getRouteMatch().getParam('connection'),callback=this.getMvcEvent().getRouteMatch().getParam('callback'),connection=this.connectionsCollection.get(connectionName),_processConnection=function(model){model.destroy().done(function(){callback(true);}).fail(function(jqX){$CL.app().alert("Failed removing connection. Server Response: "+jqX.responseText);callback(false);});};this.getMvcEvent().stopPropagation();if(!connection){this.connectionsCollection.fetch({'success':function(col){connection=col.get(connectionName);if(!connection){$CL.app().alert('Can not find connection ":connection"'.replace(':connection',connectionName));callback(false);}
_processConnection(connection);},'error':function(col,jqX){$CL.app().alert("Failed fetching connections. Server Response: "+jqX.responseText);callback(false);}})
return;}
_processConnection(connection);}});var Module=$CL.namespace('Cl.Application.Module');$CL.require("Cl.Core.String");$CL.require("Cl.Application.Module.ModuleInterface");Module.AbstractModule=function ClAbstractModule(){};Module.AbstractModule.prototype={getController:function(controllerName){var namespace=$CL.className(this).replace(/^([^\.]+)\..*$/,"$1");var con=$CL.get(namespace+".Controller."+controllerName.ucfirst());if(_.isNull(con)){var conName=namespace+".Controller."+controllerName.ucfirst();$CL.exception("Can not get "+conName+" from ServiceManager",$CL.className(this));}
return con;}};var SqlConnect=$CL.namespace('SqlConnect');$CL.require("Cl.Application.Module.AbstractModule");$CL.require("SqlConnect.Controller.Index");$CL.require("SqlConnect.Controller.Sources");$CL.require("SqlConnect.Controller.Targets");$CL.require("SqlConnect.Collection.SourceTables");$CL.require("SqlConnect.Collection.TargetTables");$CL.require("SqlConnect.Collection.Connections");$CL.require("SqlConnect.Model.Db.TableSource");$CL.require("SqlConnect.Model.Db.TableTarget");$CL.require("SqlConnect.View.Sources.Index");$CL.require("SqlConnect.View.Sources.Show");$CL.require("SqlConnect.View.Targets.Index");$CL.require("SqlConnect.View.Targets.Show");SqlConnect.Module=function(){this.__IMPLEMENTS__=[Cl.Application.Module.ModuleInterface];};SqlConnect.Module=$CL.extendClass(SqlConnect.Module,Cl.Application.Module.AbstractModule,{getConfig:function(){return{router:{routes:{'sqlconnect_index':{route:'sqlconnect/',callback:function(){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"index"});},build:function(routeParams){return this.route;}},'sqlconnect_configuration':{route:'sqlconnect/configuration/',callback:function(){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"configuration"});},build:function(routeParams){return this.route;}},'sqlconnect_configuration_add':{route:'sqlconnect/configuration/add/',callback:function(){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"configurationEdit",params:{connection:null}});},build:function(routeParams){return this.route;}},'sqlconnect_configuration_save':{route:'sqlconnect/configuration/save/',connectionConfig:{},callback:function(){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"configurationSave",params:{connectionConfig:this.connectionConfig}});},build:function(routeParams){this.connectionConfig=routeParams.connectionConfig;return this.route;}},'sqlconnect_configuration_remove':{route:'sqlconnect/configuration/remove/:connection',customCallback:function(){},callback:function(connection){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"configurationRemove",params:{connection:connection,callback:this.customCallback}});},build:function(routeParams){if($CL.isDefined(routeParams.callback)){this.customCallback=routeParams.callback;}
return this.route.replace(':connection',routeParams.connection);}},'sqlconnect_configuration_edit':{route:'sqlconnect/configuration/edit/:connection',callback:function(connection){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"index",action:"configurationEdit",params:{connection:connection}});},build:function(routeParams){return this.route.replace(':connection',routeParams.connection);}},'sqlconnect_sources':{route:'sqlconnect/sources/:connection/',callback:function(connection){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"sources",action:"index",params:{connection:connection}});},build:function(routeParams){return this.route.replace(':connection',routeParams.connection||'none');}},'sqlconnect_source':{route:'sqlconnect/sources/:connection/:action/:id',callback:function(connection,action,id){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"sources",action:action,params:{id:id,connection:connection}});},build:function(routeParams){return this.route.replace(':connection',routeParams.connection).replace(':action',routeParams.action).replace(':id',routeParams.id);}},'sqlconnect_targets':{route:'sqlconnect/targets/:connection/',callback:function(connection){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"targets",action:"index",params:{connection:connection}});},build:function(routeParams){return this.route.replace(':connection',routeParams.connection||'none');}},'sqlconnect_target':{route:'sqlconnect/targets/:connection/:action/:id',callback:function(connection,action,id){return $CL.makeObj("Cl.Application.Router.RouteMatch",{module:"sqlConnect",controller:"targets",action:action,params:{id:id,connection:connection}});},build:function(routeParams){return this.route.replace(':connection',routeParams.connection).replace(':action',routeParams.action).replace(':id',routeParams.id);}}}},service_manager:{factories:{'SqlConnect.Controller.Index':function(sl){var c=$CL.makeObj('SqlConnect.Controller.Index');c.setModuleCollection(sl.get('Ginger.Application.Collection.Modules'));c.setConnectionsCollection(sl.get('SqlConnect.Collection.Connections'));return c;},'SqlConnect.Controller.Sources':function(sl){var c=$CL.makeObj('SqlConnect.Controller.Sources');c.setSourceCollection(sl.get("SqlConnect.Collection.SourceTables"));c.setConnectionsCollection(sl.get("SqlConnect.Collection.Connections"));return c;},'SqlConnect.Controller.Targets':function(sl){var c=$CL.makeObj('SqlConnect.Controller.Targets');c.setTargetCollection(sl.get("SqlConnect.Collection.TargetTables"));c.setConnectionsCollection(sl.get("SqlConnect.Collection.Connections"));return c;},'SqlConnect.Model.Db.TableSource':function(sl){var m=$CL.makeObj('SqlConnect.Model.Db.TableSource');m.setSourceInfoCollection(sl.get('Ginger.Jobs.Collection.SourceInfos'));return m;},'SqlConnect.View.Sources.Index':function(sl){var v=$CL.makeObj('SqlConnect.View.Sources.Index');v.setSourceCollection(sl.get('SqlConnect.Collection.SourceTables'));return v;},'SqlConnect.View.Targets.Index':function(sl){var v=$CL.makeObj('SqlConnect.View.Targets.Index');v.setTargetCollection(sl.get("SqlConnect.Collection.TargetTables"));return v;}}}};}});