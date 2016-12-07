package com.trinity.sosyalpusula.service;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

public class LocationReceiver extends BroadcastReceiver {

    private static final long ZAMAN_ARALIGI = 60 * 1000;

    @Override
    public void onReceive(Context context, Intent intent) {
        // TODO: This method is called when the BroadcastReceiver is receiving
        // an Intent broadcast.

        Log.i("Locationnnn Receiver", "Started");

        AlarmManager manager = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);
        Intent serviceIntent = new Intent(context, LocationUpdate.class);
        PendingIntent pendingIntent = PendingIntent.getService(context, 1, serviceIntent, 0);
        manager.setRepeating(AlarmManager.ELAPSED_REALTIME_WAKEUP, ZAMAN_ARALIGI, ZAMAN_ARALIGI, pendingIntent);
    }
}
