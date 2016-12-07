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
import android.widget.Button;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.config.Config;
import com.trinity.sosyalpusula.helper.RequestHandler;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Taylan on 21.05.2016.
 */
public class FragmentCreateJoin extends Fragment implements ListView.OnItemClickListener {

    private Button btnBack;
    private ListView listView;
    private String JSON_STRING;

    @Override
    public View onCreateView(LayoutInflater inflater,
                             @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.fragment_createjoin, container, false);

        btnBack= (Button) v.findViewById(R.id.createJoinBack);
        listView = (ListView) v.findViewById(R.id.listViewCreateJoin);
        listView.setOnItemClickListener(this);
        getJSON();

        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                        Fragment LocationFragment = new FragmentCreate();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();

                        FragmentManager fragmentManager = getChildFragmentManager();
                        transaction.replace(R.id.main_content, LocationFragment);
                        //transaction.addToBackStack(null);
                        transaction.commit();

            }
        });
       // Toast.makeText(getActivity(), HomeActivity.fragmentCreateActivityID, Toast.LENGTH_SHORT).show();


        return v;
    }

    private void showEmployee() {
        JSONObject jsonObject = null;
        ArrayList<HashMap<String, String>> list = new ArrayList<HashMap<String, String>>();
        try {
            jsonObject = new JSONObject(JSON_STRING);
            JSONArray result = jsonObject.getJSONArray(Config.TAG_JSON_ARRAY);

            for (int i = 0; i < result.length(); i++) {
                JSONObject jo = result.getJSONObject(i);
                String userNameJoin = jo.getString("user_name");
                String joinID = jo.getString("id");

                Log.i("Joined Username", userNameJoin);


                HashMap<String, String> create = new HashMap<>();
                create.put("user_name", userNameJoin);
                create.put("id", joinID);
                list.add(create);

            }

        } catch (JSONException e) {
            e.printStackTrace();
        }

        ListAdapter adapter = new SimpleAdapter(
                getActivity(), list, R.layout.item_list_join,
                new String[]{"user_name", ""},
                new int[]{R.id.joinActivityName, R.id.joinActivityUsername});

        listView.setAdapter(adapter);

    }

    private void getJSON() {
        class GetJSON extends AsyncTask<Void, Void, String> {

            ProgressDialog loading;

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(getActivity(), "Liste Yükleniyor", "Bekleyiniz...", false, false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                JSON_STRING = s;
                //Toast.makeText(getActivity(), s, Toast.LENGTH_SHORT).show();
                showEmployee();

            }

            @Override
            protected String doInBackground(Void... params) {
                RequestHandler rh = new RequestHandler();
                String s = rh.sendGetRequestParam(Config.URL_GEL_ALL_JOINED, HomeActivity.fragmentCreateActivityID);
                return s;
            }
        }
        GetJSON gj = new GetJSON();
        gj.execute();
    }

    @Override
    public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
       /* Intent intent = new Intent(this, ViewEmployee.class);
        HashMap<String,String> map =(HashMap)parent.getItemAtPosition(position);
        String empId = map.get(Config.TAG_ID).toString();
        intent.putExtra(Config.EMP_ID,empId);
        startActivity(intent);*/

        // ListView Clicked item index
        int itemPosition = position;

        // ListView Clicked item value
        HashMap<String,String> map =(HashMap)parent.getItemAtPosition(position);
        String itemValue = map.get("id").toString();

        //Toast.makeText(getActivity(), itemValue, Toast.LENGTH_SHORT).show();
    }

}
