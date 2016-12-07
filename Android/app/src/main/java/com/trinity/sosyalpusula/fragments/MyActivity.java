package com.trinity.sosyalpusula.fragments;

import android.app.ProgressDialog;
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
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.github.jjobes.slidedatetimepicker.SlideDateTimeListener;
import com.github.jjobes.slidedatetimepicker.SlideDateTimePicker;
import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.helper.ServiceHandler;
import com.trinity.sosyalpusula.models.Category;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

/**
 * Created by Taylan on 24.04.2016.
 */
public class MyActivity extends Fragment {

    private EditText edit_title,edit_detail,edit_max_member;
    private Button btn_dateStart,btn_dateEnd,btnLocation,btnCreateActivity;
    private TextView textStart,textEnd;
    private SimpleDateFormat mFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
    private Date minDateEnd;
    private Spinner spin_category;
    private ArrayList<Category> categoriesList;
    ProgressDialog pDialog;
    String title,detail,max_member;
    private String URL_CATEGORIES = "http://52.38.97.233/getCategory.php";



    @Override
    public View onCreateView(LayoutInflater inflater,
                             @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.fragment_activity, container, false);

        edit_title = (EditText) v.findViewById(R.id.title);
        edit_detail = (EditText) v.findViewById(R.id.detail);
        edit_max_member = (EditText) v.findViewById(R.id.max_member);
        btn_dateStart = (Button) v.findViewById(R.id.btnDateStart);
        btn_dateEnd = (Button) v.findViewById(R.id.btnDateEnd);
        btnCreateActivity = (Button) v.findViewById(R.id.btnCreateActivity);
        textStart= (TextView) v.findViewById(R.id.text_start);
        textEnd = (TextView) v.findViewById(R.id.text_end);
        spin_category = (Spinner) v.findViewById(R.id.spin_category);


        btn_dateEnd.setEnabled(false);

        btn_dateStart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                new SlideDateTimePicker.Builder(getChildFragmentManager())
                        .setListener(listenerStart)
                        .setInitialDate(new Date())
                                //.setMinDate(minDate)
                                //.setMaxDate(maxDate)
                                .setIs24HourTime(true)
                                //.setTheme(SlideDateTimePicker.HOLO_DARK)
                                //.setIndicatorColor(Color.parseColor("#990000"))
                        .build()
                        .show();
            }
        });

        btn_dateEnd.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                new SlideDateTimePicker.Builder(getChildFragmentManager())
                        .setListener(listenerEnd)
                        .setInitialDate(new Date())
                                .setMinDate(minDateEnd)
                                //.setMaxDate(maxDate)
                                .setIs24HourTime(true)
                                //.setTheme(SlideDateTimePicker.HOLO_DARK)
                                //.setIndicatorColor(Color.parseColor("#990000"))
                        .build()
                        .show();
            }
        });


        ///Categoryyyyyyyyyyyyyyyyy

        categoriesList = new ArrayList<Category>();

        new GetCategories().execute();

        spin_category.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

                HomeActivity.activity_category = spin_category.getSelectedItem().toString();

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });


        //locationnnn

        btnCreateActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                title = edit_title.getText().toString().trim();
                detail = edit_detail.getText().toString().trim();
                max_member = edit_max_member.getText().toString().trim();

                HomeActivity.activity_title=title;
                HomeActivity.activity_detail=detail;
                HomeActivity.activity_maxMember=max_member;

                Fragment LocationFragment = new MyLocation();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();

                FragmentManager fragmentManager = getChildFragmentManager();
                transaction.replace(R.id.main_content, LocationFragment);
                //transaction.addToBackStack(null);
                transaction.commit();

            }
        });
        return v;
    }

    private class GetCategories extends AsyncTask<Void, Void, Void> {

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Yükleniyor..");
            pDialog.setCancelable(false);
            pDialog.show();

        }

        @Override
        protected Void doInBackground(Void... arg0) {
            ServiceHandler jsonParser = new ServiceHandler();
            String json = jsonParser.makeServiceCall(URL_CATEGORIES, ServiceHandler.GET);

            Log.e("Response: ", "> " + json);

            if (json != null) {
                try {
                    JSONObject jsonObj = new JSONObject(json);
                    if (jsonObj != null) {
                        JSONArray categories = jsonObj
                                .getJSONArray("categories");

                        for (int i = 0; i < categories.length(); i++) {
                            JSONObject catObj = (JSONObject) categories.get(i);
                            Category cat = new Category(catObj.getInt("id"),
                                    catObj.getString("name"));
                            categoriesList.add(cat);
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            } else {
                Log.e("JSON Data", "Didn't receive any data from server!");
            }

            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            if (pDialog.isShowing())
                pDialog.dismiss();
                populateSpinner();
        }

    }

    /**
     * Adding spinner data
     * */
    private void populateSpinner() {
        List<String> lables = new ArrayList<String>();


        for (int i = 0; i < categoriesList.size(); i++) {
            lables.add(categoriesList.get(i).getName());
        }

        // Creating adapter for spinner
        ArrayAdapter<String> spinnerAdapter = new ArrayAdapter<String>(getActivity(),
                android.R.layout.simple_spinner_item, lables);

        // Drop down layout style - list view with radio button
        spinnerAdapter
                .setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        // attaching data adapter to spinner
        spin_category.setAdapter(spinnerAdapter);
    }

    private SlideDateTimeListener listenerStart = new SlideDateTimeListener() {

        @Override
        public void onDateTimeSet(Date date)
        {
            //Toast.makeText(getActivity(), mFormatter.format(date), Toast.LENGTH_SHORT).show();

            textStart.setText(mFormatter.format(date));
            HomeActivity.activity_dateStart=mFormatter.format(date);
            btn_dateEnd.setEnabled(true);
            minDateEnd=date;


        }

        // Optional cancel listener
        @Override
        public void onDateTimeCancel()
        {
            Toast.makeText(getActivity(),
                    "Seçilmedi", Toast.LENGTH_SHORT).show();
        }
    };


    private SlideDateTimeListener listenerEnd = new SlideDateTimeListener() {

        @Override
        public void onDateTimeSet(Date date)
        {
            //Toast.makeText(getActivity(),
              //      mFormatter.format(date), Toast.LENGTH_SHORT).show();

            textEnd.setText(mFormatter.format(date));
            HomeActivity.activity_dateEnd=mFormatter.format(date);
        }

        // Optional cancel listener
        @Override
        public void onDateTimeCancel()
        {
            Toast.makeText(getActivity(),
                    "Seçilmedi", Toast.LENGTH_SHORT).show();
        }
    };


}
