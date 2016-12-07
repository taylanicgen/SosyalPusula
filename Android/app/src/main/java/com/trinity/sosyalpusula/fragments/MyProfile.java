package com.trinity.sosyalpusula.fragments;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.activity.HomeActivity;
import com.trinity.sosyalpusula.config.Config;
import com.trinity.sosyalpusula.helper.RequestHandler;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;

/**
 * Created by Taylan on 24.04.2016.
 */
public class MyProfile extends Fragment {

    private EditText editTextUsername;
    private EditText editTextName;
    private EditText editTextEmail;
    private EditText editTextPhone;
    private EditText editTextPassword;
    private RadioGroup sexGroup;
    private RadioButton sexButton, sexButton2;


    private Button buttonUpdate;
    private Button buttonDelete;

    private String username;

    @Override
    public View onCreateView(LayoutInflater inflater,
                             @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        //super.onCreate(savedInstanceState);

        View v = inflater.inflate(R.layout.fragment_profile, container, false);


        editTextUsername = (EditText) v.findViewById(R.id.editTextUsername);
        editTextName = (EditText) v.findViewById(R.id.editTextName);
        editTextEmail = (EditText) v.findViewById(R.id.editTextEmail);
        editTextPhone = (EditText) v.findViewById(R.id.editTextPhone);
        sexGroup = (RadioGroup) v.findViewById(R.id.sex);


        sexButton = (RadioButton) v.findViewById(R.id.r1);
        sexButton2 = (RadioButton) v.findViewById(R.id.r2);

        buttonUpdate = (Button) v.findViewById(R.id.buttonUpdate);

        buttonUpdate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(v == buttonUpdate){
                    updateEmployee();
                }
            }
        });


        HomeActivity myHome = (HomeActivity) getActivity();

        editTextUsername.setText(myHome.username);

        username= myHome.username;

        getEmployee();

        return v;
    }

    private void getEmployee(){
        class GetEmployee extends AsyncTask<Void,Void,String> {
            ProgressDialog loading;
            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(getActivity(),"Fetching...","Wait...",false,false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                showEmployee(s);
            }

            @Override
            protected String doInBackground(Void... params) {
                RequestHandler rh = new RequestHandler();
                String s = rh.sendGetRequestParam(Config.URL_GET_EMP,username);
                return s;
            }
        }
        GetEmployee ge = new GetEmployee();
        ge.execute();
    }

    private void showEmployee(String json){
        try {
            JSONObject jsonObject = new JSONObject(json);
            JSONArray result = jsonObject.getJSONArray(Config.TAG_JSON_ARRAY);
            JSONObject c = result.getJSONObject(0);
            String name = c.getString(Config.TAG_NAME);
            String email = c.getString(Config.TAG_EMAIL);
            String phone = c.getString(Config.TAG_PHONE);
            String sex = c.getString(Config.TAG_SEX);

            editTextName.setText(name);
            editTextEmail.setText(email);
            editTextPhone.setText(phone);

            if(sex.equals("M")){

                sexButton.setChecked(true);

            }
            else
            {
                sexButton2.setChecked(true);
            }

        } catch (JSONException e) {
            e.printStackTrace();
           // Toast.makeText(getActivity(), e.toString(), Toast.LENGTH_LONG).show();
        }
    }


    private void updateEmployee(){
        final String name = editTextName.getText().toString().trim();
        final String email = editTextEmail.getText().toString().trim();
        final String phone = editTextPhone.getText().toString().trim();

        final String sex;

        if(sexButton.isChecked()){
            sex = "M";
        }
        else{
            sex= "F";
        }


        class UpdateEmployee extends AsyncTask<Void,Void,String>{
            ProgressDialog loading;
            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(getActivity(),"Updating...","Wait...",false,false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                //Toast.makeText(getActivity(),s,Toast.LENGTH_LONG).show();
            }

            @Override
            protected String doInBackground(Void... params) {
                HashMap<String,String> hashMap = new HashMap<>();
                hashMap.put(Config.KEY_USERNAME,username);
                hashMap.put(Config.KEY_NAME,name);
                hashMap.put(Config.KEY_EMAIL,email);
                hashMap.put(Config.KEY_PHONE,phone);
                hashMap.put(Config.KEY_SEX,sex);

                RequestHandler rh = new RequestHandler();

                String s = rh.sendPostRequest(Config.URL_UPDATE_EMP,hashMap);

                return s;
            }
        }

        UpdateEmployee ue = new UpdateEmployee();
        ue.execute();
    }

    /* private void deleteEmployee(){
        class DeleteEmployee extends AsyncTask<Void,Void,String> {
            ProgressDialog loading;

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(ViewEmployee.this, "Updating...", "Wait...", false, false);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                Toast.makeText(ViewEmployee.this, s, Toast.LENGTH_LONG).show();
            }

            @Override
            protected String doInBackground(Void... params) {
                RequestHandler rh = new RequestHandler();
                String s = rh.sendGetRequestParam(Config.URL_DELETE_EMP, id);
                return s;
            }
        }

        DeleteEmployee de = new DeleteEmployee();
        de.execute();
    }

    private void confirmDeleteEmployee(){
        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
        alertDialogBuilder.setMessage("Are you sure you want to delete this employee?");

        alertDialogBuilder.setPositiveButton("Yes",
                new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface arg0, int arg1) {
                        deleteEmployee();
                        startActivity(new Intent(ViewEmployee.this,ViewAllEmployee.class));
                    }
                });

        alertDialogBuilder.setNegativeButton("No",
                new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface arg0, int arg1) {

                    }
                });

        AlertDialog alertDialog = alertDialogBuilder.create();
        alertDialog.show();
    }*/

}
