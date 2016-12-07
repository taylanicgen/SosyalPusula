package com.trinity.sosyalpusula.location;

/**
 * Created by Taylan on 25.04.2016.
 */

import android.Manifest;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.app.Service;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.provider.Settings;
import android.support.v4.content.ContextCompat;
import android.util.Log;
import android.view.View;

public class LocationFinder extends Service {


    ////////////////////////////////////
    /////       VARIABLES
    ////////////////////////////////////

    private Activity activity;
    private View view = null;
    private LocationManager mLocationManager;
    private Boolean isGpsEnable = false;
    private Boolean isNetworkEnable = false;
    private Handler gpsHandler;
    private ProgressDialog mProgresDialog = null;
    private OnLocationCustomEvent customGpsVariable = null;


    ////////////////////////////////////
    /////       METHODS
    ////////////////////////////////////

    private void creatLocation() {
        prepareLocation();
    }

    private void prepareLocation() {

        if (mLocationManager == null)
            mLocationManager = (LocationManager) activity.getApplicationContext().getSystemService(LOCATION_SERVICE);

        try {
            isGpsEnable = mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        } catch (Exception ex) {
        }
        try {
            isNetworkEnable = mLocationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
        } catch (Exception ex) {
        }

        Log.v("", "DURUM ------GPS:" + isGpsEnable + " -----NETWORK:" + isNetworkEnable);

        if (isGpsEnable == false) {
            gpsOpenPopUp();
        } else {
            gpsWaiting();
            setListener();
        }
    }


    /**
     * Gps açıksa network ve gps providerları çalıştırıyoruz
     * */
    private void setListener() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (ContextCompat.checkSelfPermission(this.activity,Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ContextCompat.checkSelfPermission(this.activity, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                // TODO: Consider calling
                //    public void requestPermissions(@NonNull String[] permissions, int requestCode)
                // here to request the missing permissions, and then overriding
                //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                //                                          int[] grantResults)
                // to handle the case where the user grants the permission. See the documentation
                // for Activity#requestPermissions for more details.
                return;
            }
        }
        mLocationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, networkLocationListener);
        mLocationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, gpsLocationListener);
    }


    //GPS OPEN ALERT WINDOWS
    private void gpsOpenPopUp() {
        if (view != null) {
            AlertDialog.Builder alert = new AlertDialog.Builder(view.getContext());
            alert.setTitle("Gps ayarları");
            alert.setMessage("Gpsiniz kapalı.Ayarlar menusunden gpsi açmak ister misiniz?");
            alert.setPositiveButton("Evet", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    Intent androinSetting = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                    activity.startActivity(androinSetting);
                    mLaunchTask.run();
                }
            });
            alert.show();
        }
    }
    //GPS OPEN ALERT W‹NDOWS


    //IS GPS OPEN TEST
    public Runnable mLaunchTask = new Runnable() {
        public void run() {
            try {
                Log.v("", "gps acıldımı? Runnable()");
                gpsWaiting();
                gpsHandler = new Handler();
                if (mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                    gpsHandler.removeCallbacks(mLaunchTask);
                    setListener();
                    Log.v("", "gps acıldı)");
                } else {
                    gpsHandler.postDelayed(mLaunchTask, 1000);
                }
            } catch (IllegalStateException e) {
                e.printStackTrace();
            }
        }
    };
    //IS GPS OPEN TEST

    /**
     * Gps open popup
     * */
    private void gpsWaiting() {
        if (mProgresDialog == null) {
            mProgresDialog = new ProgressDialog(view.getContext());
            mProgresDialog.setMessage("Lokasyon Bilgileri bekleniyor...");
            mProgresDialog.setCancelable(false);
            mProgresDialog.show();
        }
    }


    private void clearGpsWaitingPopUp() {
        Log.v("", "clearGpsWaitingPopUp");
        if (mProgresDialog != null) {
            mProgresDialog.cancel();
            mProgresDialog = null;
        }
    }


    ////////////////////////////////////
    /////       EVENT
    ////////////////////////////////////

    /**
     * Gps Location Listener
     * */
    LocationListener gpsLocationListener = new LocationListener() {
        public void onLocationChanged(Location location) {
            Log.v("", "locationListenerGps" + location.getLatitude());
            if (location != null) {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                    if (ContextCompat.checkSelfPermission(activity, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ContextCompat.checkSelfPermission(activity,Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                        // TODO: Consider calling
                        //    public void requestPermissions(@NonNull String[] permissions, int requestCode)
                        // here to request the missing permissions, and then overriding
                        //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                        //                                          int[] grantResults)
                        // to handle the case where the user grants the permission. See the documentation
                        // for Activity#requestPermissions for more details.
                        return;
                    }
                }
                mLocationManager.removeUpdates(networkLocationListener);
                mLocationManager.removeUpdates(this);
                clearGpsWaitingPopUp();
                customGpsVariable.getLatLon(location.getLatitude(), location.getLongitude());
            }
        }

        public void onProviderDisabled(String provider) {
        }

        public void onProviderEnabled(String provider) {
        }

        public void onStatusChanged(String provider, int status, Bundle extras) {
        }
    };


    /**
     * Network Location Listener
     * */
    LocationListener networkLocationListener = new LocationListener() {
        public void onLocationChanged(Location location) {
            Log.v("", "locationListenerNetwork" + location.getLatitude());
            if (location != null) {
                if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.M) {
                    if (ContextCompat.checkSelfPermission(activity,Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ContextCompat.checkSelfPermission(activity, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                        // TODO: Consider calling
                        //    public void requestPermissions(@NonNull String[] permissions, int requestCode)
                        // here to request the missing permissions, and then overriding
                        //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                        //                                          int[] grantResults)
                        // to handle the case where the user grants the permission. See the documentation
                        // for Activity#requestPermissions for more details.
                        return;
                    }
                }
                mLocationManager.removeUpdates(this);
                clearGpsWaitingPopUp();
                customGpsVariable.getLatLon(location.getLatitude(), location.getLongitude());
            }
        }
        public void onProviderDisabled(String provider) {}
        public void onProviderEnabled(String provider) {}
        public void onStatusChanged(String provider, int status, Bundle extras) {}
    };








    ////////////////////////////////////
    /////       GETTER SETTER
    ////////////////////////////////////
    public void setActivity(Activity value){
        this.activity = value;
    }

    public void setStart(){
        creatLocation();
    }

    public void setView(View value){
        this.view = value;
    }

    @Override
    public IBinder onBind(Intent intent) {
        // TODO Auto-generated method stub
        return null;
    }








    //custom event listener interface
    public interface OnLocationCustomEvent {
        public abstract void getLatLon(Double lat, Double lon);
    }

    //listener trigger
    public void setOnLocationListener(OnLocationCustomEvent listener) {
        customGpsVariable = listener;
    }


}
