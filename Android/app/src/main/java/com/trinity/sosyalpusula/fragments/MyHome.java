package com.trinity.sosyalpusula.fragments;

import android.graphics.Bitmap;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapView;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.maps.android.ui.IconGenerator;
import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.helper.RequestHandler;
import com.trinity.sosyalpusula.location.LocationProvider;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.List;
import java.util.Locale;


public class MyHome extends Fragment implements  LocationProvider.LocationCallback {

    private GoogleMap googleMap;
    private LocationProvider mLocationProvider;
    MapView mMapView;
    public double currentLatitude=0,currentLongitude=0;
    public static final String TAG = MyHome.class.getSimpleName();
    @Override
	public View onCreateView(LayoutInflater inflater,
							 @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        super.onCreateView(inflater,container,savedInstanceState);
        final View v = inflater.inflate(R.layout.fragment_home, container, false);


        mMapView = (MapView) v.findViewById(R.id.mapView);
        mMapView.onCreate(savedInstanceState);

        mMapView.onResume();// needed to get the map to display immediately

        try {
            MapsInitializer.initialize(getActivity().getApplicationContext());
        } catch (Exception e) {
            e.printStackTrace();
        }

        googleMap = mMapView.getMap();

        mLocationProvider = new LocationProvider(getActivity(), this);
        googleMap.setMyLocationEnabled(true);


        //Location loc = LocationProvider.returnMyLocation();
        //Toast.makeText(getActivity(), currentLatitude+"/n"+currentLongitude, Toast.LENGTH_LONG).show();

                        googleMap.setOnMapClickListener(new GoogleMap.OnMapClickListener() {

                            @Override
                            public void onMapClick(LatLng arg0) {
                                // TODO Auto-generated method stub
                              /*  Log.d("arg0", arg0.latitude + "-" + arg0.longitude);
                                MarkerOptions options = new MarkerOptions()
                                        .position(arg0)
                                        .title("I am here!");
                                googleMap.addMarker(options);
                               Toast.makeText(getActivity(), "arg0 " + arg0.latitude + "-" + arg0.longitude, Toast.LENGTH_SHORT).show();
                               */
                            }
                        });

        return v;
    }


    @Override
    public void onResume() {
        super.onResume();
        mLocationProvider.connect();
    }

    @Override
    public void onPause() {
        super.onPause();
        mLocationProvider.disconnect();
    }

    @Override
    public void handleNewLocation(Location location) throws IOException {
        Log.d(TAG, location.toString());

        currentLatitude = location.getLatitude();
        currentLongitude = location.getLongitude();

        LatLng latLng = new LatLng(currentLatitude, currentLongitude);

        //googleMap.moveCamera(CameraUpdateFactory.newLatLng(latLng));
        CameraPosition cameraPosition = new CameraPosition.Builder()
                .target(new LatLng(currentLatitude, currentLongitude)).zoom(10).build();
        googleMap.animateCamera(CameraUpdateFactory
                .newCameraPosition(cameraPosition));

        Geocoder geocoder = new Geocoder(getActivity().getApplicationContext(), new Locale("tr", "TR"));
        List<Address> adresListesi = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(), 1);

        String adresString = null;
        if(adresListesi != null && adresListesi.size() > 0) {

            Address adres = adresListesi.get(0);
            String adresSatiri = adres.getMaxAddressLineIndex() > 0 ? adres.getAddressLine(0) : "";
            String postaKodu = adres.getPostalCode();
            String ulke = adres.getCountryName();
            adresString = String.format("%s, %s, %s", adresSatiri, postaKodu, ulke);

        }

        sendLatLng_in_Server(currentLatitude, currentLongitude);


      /*  MarkerOptions options = new MarkerOptions()
                .position(latLng)
                .title(adresString);
        googleMap.addMarker(options);
        */
    }

    private void sendLatLng_in_Server(double latitude,double longitude){

        //StrictMode kullanarak,ağ erişiminin güvenli bir şekilde yapılmasını sağlıyoruz...
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);

        Log.i("HEY", "1." + latitude + "        2." + longitude);
        String wcfUrl="http://52.38.97.233/activity/activityAllFinder.php";
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
            final JSONArray jArray=jsonResponse.getJSONArray("Android");
            //Konumuma en yakın olan yerlerin enlem ve boylam değerlerini sunucudan aldım.
            for(int i=0;i<jArray.length();i++) {
                JSONObject json_data=jArray.getJSONObject(i);
                String activity_name = json_data.getString("activity_name");
                Double lat= json_data.getDouble("latitude");
                Double lng= json_data.getDouble("longitude");
                Marker marker;
                if(jArray!=null){

                    LatLng latLng = new LatLng(lat, lng);

                    IconGenerator mIconGenerator = new IconGenerator(getContext());
                    mIconGenerator.setColor(Color.GREEN);
                    Bitmap iconBitmap = mIconGenerator.makeIcon(activity_name);

                    MarkerOptions options = new MarkerOptions()
                            .icon(BitmapDescriptorFactory.fromBitmap(iconBitmap))
                            .position(latLng)
                            .title(activity_name);
                    googleMap.addMarker(options);

                }

                googleMap.setOnMarkerClickListener(new GoogleMap.OnMarkerClickListener() {
                    @Override
                    public boolean onMarkerClick(Marker marker) {

                        for(int i=0;i<jArray.length();i++) {
                            try {
                                JSONObject json_data = jArray.getJSONObject(i);
                                String activity_name = json_data.getString("activity_name");
                                Double lat= json_data.getDouble("latitude");
                                Double lng= json_data.getDouble("longitude");
                                String detail = json_data.getString("detail");
                                String start_date= json_data.getString("start_date");
                                String end_date= json_data.getString("end_date");
                                Integer max_member = json_data.getInt("max_member");
                                String user_name= json_data.getString("user_name");
                                String category_name= json_data.getString("category_name");
                                String address=json_data.getString("address");
                                Integer activity_id=json_data.getInt("activity_id");

                                LatLng latLng = new LatLng(lat, lng);

                                if (marker.getPosition().equals(latLng)){


                                    HomeActivity.joinActivity_category=category_name;
                                    HomeActivity.joinActivity_detail=detail;
                                    HomeActivity.joinActivity_maxMember=max_member;
                                    HomeActivity.joinActivity_end=end_date;
                                    HomeActivity.joinActivity_start=start_date;
                                    HomeActivity.joinActivity_title=activity_name;
                                    HomeActivity.joinActivity_username=user_name;
                                    HomeActivity.joinActivity_address=address;
                                    HomeActivity.joinActivity_activity_id = activity_id;

                                    //Toast.makeText(getActivity(), activity_name, Toast.LENGTH_LONG).show();

                                    Fragment LocationFragment = new FragmentJoinActivity();
                                    FragmentTransaction transaction = getFragmentManager().beginTransaction();

                                    FragmentManager fragmentManager = getChildFragmentManager();
                                    transaction.replace(R.id.main_content, LocationFragment);
                                    //transaction.addToBackStack(null);
                                    transaction.commit();

                                    Log.i("Etkinlik Bilgileri", activity_name);
                                    Log.i("Etkinlik Bilgileri", lat.toString());
                                    Log.i("Etkinlik Bilgileri",lng.toString());
                                    Log.i("Etkinlik Bilgileri",detail);
                                    Log.i("Etkinlik Bilgileri",start_date);
                                    Log.i("Etkinlik Bilgileri",end_date);
                                    Log.i("Etkinlik Bilgileri",max_member.toString());
                                    Log.i("Etkinlik Bilgileri",user_name);
                                    Log.i("Etkinlik Bilgileri",category_name);

                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                        return true;
                    }
                });

            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}