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
import android.widget.Button;
import android.widget.TextView;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.config.Config;
import com.trinity.sosyalpusula.helper.RequestHandler;

import java.util.HashMap;

/**
 * Created by Taylan on 15.05.2016.
 */
public class FragmentJoinActivity extends Fragment {

    private TextView username,title,detail,category,starDate,endDate,maxMember,address;
    private Button btnBack,btnJoin;
    @Override
    public View onCreateView(LayoutInflater inflater,
                             @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.fragment_join_activity, container, false);


        username = (TextView) v.findViewById(R.id.join_activity_username);
        title = (TextView) v.findViewById(R.id.join_activity_title);
        detail= (TextView) v.findViewById(R.id.join_activity_detail);
        category= (TextView) v.findViewById(R.id.join_activity_category);
        starDate= (TextView) v.findViewById(R.id.join_activity_starDate);
        endDate= (TextView) v.findViewById(R.id.join_activity_endDate);
        maxMember = (TextView) v.findViewById(R.id.join_activity_maxMember);
        address= (TextView) v.findViewById(R.id.join_activity_address);
        btnBack= (Button) v.findViewById(R.id.btn_join_activity_back);
        btnJoin= (Button) v.findViewById(R.id.btn_join_activity_join);

        username.setText(HomeActivity.joinActivity_username);
        title.setText(HomeActivity.joinActivity_title);
        detail.setText(HomeActivity.joinActivity_detail);
        category.setText(HomeActivity.joinActivity_category);
        maxMember.setText(Integer.toString(HomeActivity.joinActivity_maxMember));
        starDate.setText(HomeActivity.joinActivity_start);
        endDate.setText(HomeActivity.joinActivity_end);
        address.setText(HomeActivity.joinActivity_address);

        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                Fragment LocationFragment = new MyHome();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();

                FragmentManager fragmentManager = getChildFragmentManager();
                transaction.replace(R.id.main_content, LocationFragment);
                //transaction.addToBackStack(null);
                transaction.commit();
            }
        });

        btnJoin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                joinActivity();
            }
        });

        return v;
    }

    public void joinActivity(){


        Log.i("Insert", "user_name= " + HomeActivity.username);
        Log.i("Insert", "activity_id= " + HomeActivity.joinActivity_activity_id);




        class AddEmployee extends AsyncTask<Void,Void,String> {

            ProgressDialog loading;

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(getActivity(),"İşlem Yürütülüyor...","Bekleyiniz...",false,false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                //Toast.makeText(getActivity(), s, Toast.LENGTH_LONG).show();
            }

            @Override
            protected String doInBackground(Void... v) {
                HashMap<String,String> params = new HashMap<>();
                params.put("user_name",HomeActivity.username);
                params.put("activity_id",Integer.toString(HomeActivity.joinActivity_activity_id));

                RequestHandler rh = new RequestHandler();
                String res = rh.sendPostRequest(Config.URL_JOIN, params);
                return res;
            }
        }

        AddEmployee ae = new AddEmployee();
        ae.execute();

    }
}
