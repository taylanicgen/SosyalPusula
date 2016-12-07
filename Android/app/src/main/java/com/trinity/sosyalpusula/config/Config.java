package com.trinity.sosyalpusula.config;

/**
 * Created by Taylan on 1.05.2016.
 */
public class Config {

    //Address of our scripts of the CRUD
    public static final String URL_GET_EMP = "http://52.38.97.233/android_login_api/getUser.php?username=";
    public static final String URL_UPDATE_EMP = "http://52.38.97.233/android_login_api/updateUser.php";
    public static final String URL_UPDATE_LOC = "http://52.38.97.233/android_login_api/updateLocation.php";
    public static final String URL_ADD = "http://52.38.97.233/activity/addActivity.php";
    public static final String URL_JOIN = "http://52.38.97.233/activity/joinActivity.php";
    public static final String URL_GEL_ALL_JOIN = "http://52.38.97.233/activity/getAllActivityJoin.php?username=";
    public static final String URL_GEL_ALL_CREATE = "http://52.38.97.233/activity/getAllActivityCreate.php?username=";
    public static final String URL_GEL_ALL_JOINED = "http://52.38.97.233/activity/getAllActivityJoined.php?activity_id=";





    //Keys that will be used to send the request to php scripts
    public static final String KEY_USERNAME = "username";
    public static final String KEY_NAME = "name";
    public static final String KEY_PHONE = "phone";
    public static final String KEY_EMAIL = "email";
    public static final String KEY_SEX = "sex";
    public static final String KEY_PASSWORD = "password";

    //JSON Tags
    public static final String TAG_JSON_ARRAY="result";
    public static final String TAG_USERNAME = "username";

    public static final String TAG_NAME = "name";
    public static final String TAG_PHONE = "phone";
    public static final String TAG_EMAIL = "email";
    public static final String TAG_SEX = "sex";
    public static final String TAG_PASSWORD = "password";
    public static final String TAG_ID = "id";

    //employee id to pass with intent
    public static final String USERNAME = "emp_id";
}

