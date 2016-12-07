package com.trinity.sosyalpusula.service;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Intent;
import android.content.SharedPreferences;
import android.location.Location;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.IBinder;
import android.os.StrictMode;
import android.preference.PreferenceManager;
import android.support.annotation.Nullable;
import android.text.TextUtils;
import android.util.Log;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.config.Config;
import com.trinity.sosyalpusula.helper.RequestHandler;
import com.trinity.sosyalpusula.location.LocationProvider;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.IOException;
import java.util.HashMap;

/**
 * Created by Taylan on 2.05.2016.
 */
public class LocationUpdate extends IntentService implements LocationProvider.LocationCallback {

    private SharedPreferences tercihler;

    double currentLatitude,currentLongitude;
    LocationProvider mLocationProvider;
    public String username;
    private NotificationManager notificationManager;

    public LocationUpdate() {
        super("LocationUpdate");
    }


    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }


    @Override
    protected void onHandleIntent(Intent intent) {


        Log.i("Location Service", "Started");
        HomeActivity myHome = new HomeActivity();
        username= myHome.username;
        mLocationProvider = new LocationProvider(this, this);
        mLocationProvider.connect();
    }

    @Override
    public void handleNewLocation(Location location) throws IOException {
        //Log.i("Servicem", "Current'lari almadan önce...!!!!!!!!!!!!!!!!");
        currentLatitude = location.getLatitude();
        currentLongitude = location.getLongitude();

        updateLocation();
        sendLatLng_in_Server(currentLatitude, currentLongitude);
    }

    private void updateLocation(){
        final double loc_lat =  currentLatitude;
        final double loc_long = currentLongitude;
        Log.i("Servicem", "updateLocation Çalıştı"+String.valueOf(currentLatitude)+" -- "+String.valueOf(currentLongitude));

        class UpdateEmployee extends AsyncTask<Void,Void,String> {

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
            }

            @Override
            protected String doInBackground(Void... params) {
                HashMap<String,String> hashMap = new HashMap<>();

                hashMap.put("username",username);
                hashMap.put("location_lat",String.valueOf(currentLatitude));
                hashMap.put("location_long",String.valueOf(currentLongitude));


                RequestHandler rh = new RequestHandler();
                Log.i("Servicem", "istek gönderilecek");
                String s = rh.sendPostRequest(Config.URL_UPDATE_LOC,hashMap);
                Log.i("Servicem", s);
                return s;
            }
        }

        UpdateEmployee ue = new UpdateEmployee();
        ue.execute();
    }

    private void sendLatLng_in_Server(double latitude,double longitude){

        //StrictMode kullanarak,ağ erişiminin güvenli bir şekilde yapılmasını sağlıyoruz...
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);

        Log.i("HEY","1."+latitude+"        2."+longitude);
        String wcfUrl="http://52.38.97.233/activity/activityFinder.php";
        JSONObject obj=new JSONObject();
        String jsonString="";
        try {
            //Konum değerlerimi sunucuya gönderiyorum...
            obj.put("latitude",latitude);
            obj.put("longitude",longitude);
            RequestHandler HttpClientMy=new RequestHandler();
            jsonString=HttpClientMy.callWebService(wcfUrl, obj);

            //Json objesi olusturuyoruz..
            JSONObject jsonResponse = new JSONObject(jsonString);
            //Olusturdugumuz obje üzerinden  json string deki dataları kullanıyoruz..
            JSONArray jArray=jsonResponse.getJSONArray("Android");
            tercihler = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
            boolean bildirim = tercihler.getBoolean("PREF_UYARI", true);
            //Konumuma en yakın olan yerlerin enlem ve boylam değerlerini sunucudan aldım.
            for(int i=0;i<jArray.length();i++) {
                JSONObject json_data=jArray.getJSONObject(i);
                String activity_name = json_data.getString("activity_name");
                Log.w("Etkinlik adı", activity_name);
                Double lat= json_data.getDouble("latitude");
                Double lng= json_data.getDouble("longitude");



            }
            if(jArray!=null && bildirim){

                notificationGoster();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void notificationGoster() {

        notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
       String bildirimTonu = tercihler.getString("PREF_BILDIRIM_TONU", null);
        Uri bildirimTonuUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        if(!TextUtils.isEmpty(bildirimTonu))
            bildirimTonuUri = Uri.parse(bildirimTonu);
        Intent intent = new Intent(this, HomeActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                | Intent.FLAG_ACTIVITY_CLEAR_TASK);

        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent, 0);

        Notification.Builder builder = new Notification.Builder(this)
                .setSmallIcon(R.mipmap.help)
                .setContentTitle("Etkinliğe Katıl!")
                .setContentText("Yakınlarında oluşturulmuş bir etkinlik var!")
                .setSound(bildirimTonuUri)
                .setContentIntent(pendingIntent);

        notificationManager.notify(0, builder.build());

    }

}
