<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 01.05.2018
 * Time: 13:09
 */

namespace lo\core\helpers;

class BaseConstHelper
{

    /** Main menu */
    const B_MENU_MAIN_ROOT = 'Main';
    const B_ROUTE_MAIN_ROOT = '/site/index';

    /** System menu */
    const B_MENU_CORE_ROOT = 'System';
    const B_MENU_CORE_CACHE = 'Cache';
    const B_MENU_CORE_LOG = 'Log';
    const B_MENU_CORE_DUMPER = 'Dumper';
    const B_MENU_CORE_TRANSLATE = 'Translates';
    const B_MENU_CORE_PLUGINS = 'Plugins';
    const B_MENU_CORE_SETTINGS = 'Settings';
    const B_MENU_CORE_TEMPLATE = 'Template';

    const B_ROUTE_CORE_CACHE = '/core/cache/index';
    const B_ROUTE_CORE_LOG = '/core/log/index';
    const B_ROUTE_CORE_DUMPER = '/core/sypex-dumper/index';
    const B_ROUTE_CORE_TRANSLATE = '/i18n/i18n-message/index';
    const B_ROUTE_CORE_PLUGINS = '/plugins/plugin/index';
    const B_ROUTE_CORE_SETTINGS = '/settings/key-storage/index';
    const B_ROUTE_CORE_TEMPLATE = '/core/template/index';

    /** Content menu */
    const B_MENU_CONTENT_ROOT = 'Content';
    const B_MENU_CONTENT_MENU = 'Menu';
    const B_MENU_CONTENT_INCLUDE = 'Include Item';
    const B_MENU_CONTENT_FILES = 'File Manager';
    const B_MENU_CONTENT_COMMENTS = 'Comments';
    const B_MENU_CONTENT_GALLERY = 'Gallery';

    const B_ROUTE_CONTENT_MENU = '/main/menu/index';
    const B_ROUTE_CONTENT_INCLUDE = '/main/include-item/index';
    const B_ROUTE_CONTENT_FILES = '/elfinder/file-manager/index';
    const B_ROUTE_CONTENT_COMMENTS = '/comments/item/index';
    const B_ROUTE_CONTENT_GALLERY = '/gallery/gallery-cat/index';

    /** User menu */
    const B_MENU_USER_ROOT = 'Users (main)';
    const B_MENU_USER_USER = 'Users';
    const B_MENU_USER_RBAC = 'Rbac';
    const B_MENU_USER_CONSTRAINT = 'Constraint';
    const B_MENU_USER_MENU = 'Menu';

    const B_ROUTE_USER_USER = '/user/admin/index';
    const B_ROUTE_USER_RBAC = '/admin/assignment/index';
    const B_ROUTE_USER_CONSTRAINT = '/permission/constraint/index';
    const B_ROUTE_USER_MENU = '/admin/menu/index';
}