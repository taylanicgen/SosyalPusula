package com.trinity.sosyalpusula.fragments;

import android.app.ProgressDialog;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapView;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.config.Config;
import com.trinity.sosyalpusula.helper.RequestHandler;
import com.trinity.sosyalpusula.location.LocationProvider;

import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

/**
 * Created by Taylan on 3.05.2016.
 */
public class MyLocation extends Fragment implements LocationProvider.LocationCallback{

        private GoogleMap googleMap;
        private LocationProvider mLocationProvider;
        MapView mMapView;
        public double currentLatitude=0,currentLongitude=0;
        public static final String TAG = MyLocation.class.getSimpleName();
        public String activity_locationLat,activity_lcoationLong;
        Marker marker = null;
        private Button btn_Create;
        private Button btn_Back;
        private String adresSatiri;
    @Override
        public View onCreateView(LayoutInflater inflater,
                @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            super.onCreateView(inflater,container,savedInstanceState);
            final View v = inflater.inflate(R.layout.fragment_location, container, false);


            btn_Create = (Button) v.findViewById(R.id.btn_Create);
            btn_Back = (Button) v.findViewById(R.id.btn_back);
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

            googleMap.setOnMapClickListener(new GoogleMap.OnMapClickListener() {

                @Override
                public void onMapClick(LatLng arg0) {
                    // TODO Auto-generated method stub


                    if (marker == null) {

                        MarkerOptions options = new MarkerOptions()
                                .position(arg0)
                                .title("Etkinlik Alanı");
                        marker = googleMap.addMarker(options);

                    } else {
                        marker.remove();
                        MarkerOptions options = new MarkerOptions()
                                .position(arg0)
                                .title("Etkinlik Alanı");
                        marker = googleMap.addMarker(options);
                    }

                    activity_locationLat = String.valueOf(arg0.latitude);
                    activity_lcoationLong = String.valueOf(arg0.longitude);
                    Geocoder geocoder = new Geocoder(getActivity().getApplicationContext(), new Locale("tr", "TR"));
                    try {
                        List<Address> adresListesi = geocoder.getFromLocation(arg0.latitude, arg0.longitude, 1);
                        String adresString = null;
                        if(adresListesi != null && adresListesi.size() > 0) {

                            Address adres = adresListesi.get(0);
                            adresSatiri = adres.getMaxAddressLineIndex() > 0 ? adres.getAddressLine(0) : "";
                            String postaKodu = adres.getPostalCode();
                            String ulke = adres.getCountryName();
                            adresString = String.format("%s, %s, %s", adresSatiri, postaKodu, ulke);

                        }
                    } catch (IOException e) {
                        e.printStackTrace();
                    }


                    // Toast.makeText(getActivity(), "arg0 " + arg0.latitude + "-" + arg0.longitude, Toast.LENGTH_SHORT).show();
                }
            });

            btn_Create.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    insertActivity();
                }
            });
            btn_Back.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Fragment LocationFragment = new MyActivity();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();

                    FragmentManager fragmentManager = getChildFragmentManager();
                    transaction.replace(R.id.main_content, LocationFragment);
                    //transaction.addToBackStack(null);
                    transaction.commit();
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
            lokasyonyaz(currentLatitude);
            LatLng latLng = new LatLng(currentLatitude, currentLongitude);


            //googleMap.moveCamera(CameraUpdateFactory.newLatLng(latLng));
            CameraPosition cameraPosition = new CameraPosition.Builder()
                    .target(new LatLng(currentLatitude, currentLongitude)).zoom(15).build();
            googleMap.animateCamera(CameraUpdateFactory
                    .newCameraPosition(cameraPosition));

  /*          Geocoder geocoder = new Geocoder(getActivity().getApplicationContext(), new Locale("tr", "TR"));
            List<Address> adresListesi = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(), 1);

            String adresString = null;
            if(adresListesi != null && adresListesi.size() > 0) {

                Address adres = adresListesi.get(0);
                adresSatiri = adres.getMaxAddressLineIndex() > 0 ? adres.getAddressLine(0) : "";
                String postaKodu = adres.getPostalCode();
                String ulke = adres.getCountryName();
                adresString = String.format("%s, %s, %s", adresSatiri, postaKodu, ulke);

            }*/

        }

    void lokasyonyaz(double currentLatitude){

        //Log.d("Lokasyoooooooooon", String.valueOf(currentLatitude));

    }

    public void insertActivity(){


        Log.i("Insert", "title= "+HomeActivity.activity_title);
        Log.i("Insert", "detail= "+HomeActivity.activity_detail);
        Log.i("Insert", "max-member= "+HomeActivity.activity_maxMember);
        Log.i("Insert", "category= "+HomeActivity.activity_category);
        Log.i("Insert", "date-start= "+HomeActivity.activity_dateStart);
        Log.i("Insert", "date-end= "+HomeActivity.activity_dateEnd);
        Log.i("Insert", "location-lat= "+activity_locationLat);
        Log.i("Insert", "location-long= "+activity_lcoationLong);


        class AddEmployee extends AsyncTask<Void,Void,String> {

            ProgressDialog loading;

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(getActivity(),"Etkinlik Oluşturuluyor","Bekleyiniz...",false,false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                Toast.makeText(getActivity(), s, Toast.LENGTH_LONG).show();
            }

            @Override
            protected String doInBackground(Void... v) {
                HashMap<String,String> params = new HashMap<>();
                params.put("title",HomeActivity.activity_title);
                params.put("detail",HomeActivity.activity_detail);
                params.put("start_date",HomeActivity.activity_dateStart);
                params.put("end_date",HomeActivity.activity_dateEnd);
                params.put("location_lat",activity_locationLat);
                params.put("location_long",activity_lcoationLong);
                params.put("max_member",HomeActivity.activity_maxMember);
                params.put("user_name",HomeActivity.username);
                params.put("category_name",HomeActivity.activity_category);
                params.put("address",adresSatiri);

                RequestHandler rh = new RequestHandler();
                String res = rh.sendPostRequest(Config.URL_ADD, params);
                return res;
            }
        }

        AddEmployee ae = new AddEmployee();
        ae.execute();

    }

}
