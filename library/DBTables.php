<?php

class DBTables
{
    const ADMIN_LOG     = 'lbs_admin_log';
    
    /**
     * 基础设置
     */
    const ADMIN_SETTINGS= 'lbs_admin_settings';
    
    /**
     * 用户基本资料
     */
    const USER          = 'lbs_user';
    
    /**
     * 用户博客计数
     */
    const USER_COUNT    = 'lbs_user_count';
    
    /**
     * 用户附加资料
     */
    const USER_PROFILE  = 'lbs_user_profile';
    
    /**
     * 用户爱车信息
     */
    const USER_CAR      = 'lbs_carinfo';
    
    /**
     * 关键词过滤
     */
    const KW_FILTER     = 'lbs_keywords_deny';
    
    /**
     * 博文
     */
    const BLOG          = 'lbs_blog';
    
    /**
     * 博文计算器
     */
    const BLOG_COUNT    = 'lbs_blog_count';
    
    /**
     * 博文媒体
     */
    const URL           = 'lbs_url';
}