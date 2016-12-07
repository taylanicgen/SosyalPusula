package com.trinity.sosyalpusula.activity;

import android.os.Bundle;
import android.preference.PreferenceActivity;

import com.trinity.sosyalpusula.fragments.MySettings;

/**
 * Created by Taylan on 24.05.2016.
 */
public class SettingsActivity extends PreferenceActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getFragmentManager().beginTransaction()
                .replace(android.R.id.content, new MySettings())//Ayarlar ekrani olusumu
                .commit();
    }
}
