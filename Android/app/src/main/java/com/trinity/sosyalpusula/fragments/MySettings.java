package com.trinity.sosyalpusula.fragments;


import android.content.Intent;
import android.os.Bundle;
import android.preference.Preference;
import android.preference.PreferenceFragment;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;


public class MySettings extends PreferenceFragment  {


	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		addPreferencesFromResource(R.xml.preference);

		Preference button = (Preference)findPreference("button");
		button.setOnPreferenceClickListener(new Preference.OnPreferenceClickListener() {
			@Override
			public boolean onPreferenceClick(Preference arg0) {
				Intent intent = new Intent(getActivity(),HomeActivity.class);
				startActivity(intent);
				return true;
			}
		});
	}



}
