package com.trinity.sosyalpusula.activity;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.trinity.sosyalpusula.R;
import com.trinity.sosyalpusula.adapters.NavListAdapter;
import com.trinity.sosyalpusula.fragments.FragmentCreate;
import com.trinity.sosyalpusula.fragments.MyAbout;
import com.trinity.sosyalpusula.fragments.MyActivity;
import com.trinity.sosyalpusula.fragments.MyHome;
import com.trinity.sosyalpusula.fragments.MyProfile;
import com.trinity.sosyalpusula.helper.SQLiteHandler;
import com.trinity.sosyalpusula.helper.SessionManager;
import com.trinity.sosyalpusula.models.NavItem;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class HomeActivity extends AppCompatActivity {

    DrawerLayout drawerLayout;
    RelativeLayout drawerPane;
    private TextView txtName;
    private TextView txtEmail;
    private SQLiteHandler db;
    private SessionManager session;
    ListView lvNav;
    public static int ali = 0;
    public static String username;
    public static String activity_title,activity_detail,activity_maxMember,activity_category;
    public static String activity_dateStart,activity_dateEnd;
    public static String joinActivity_title,joinActivity_detail,joinActivity_category,joinActivity_username,joinActivity_start,joinActivity_end,joinActivity_address;
    public static int joinActivity_maxMember,joinActivity_activity_id;
    public static String fragmentCreateActivityID;



    List<NavItem> listNavItems;
    List<Fragment> listFragments;

    ActionBarDrawerToggle actionBarDrawerToggle;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        drawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawerPane = (RelativeLayout) findViewById(R.id.drawer_pane);
        txtName = (TextView) findViewById(R.id.txtName);
        txtEmail= (TextView) findViewById(R.id.txtEmail);
        lvNav = (ListView) findViewById(R.id.nav_list);

        SimpleDateFormat fmt = new SimpleDateFormat("MM-dd-yyyy HH:mm");

        session = new SessionManager(getApplicationContext());
        db = new SQLiteHandler(getApplicationContext());

        listNavItems = new ArrayList<NavItem>();
        listNavItems.add(new NavItem(getString(R.string.home), "",
                R.mipmap.home));
        listNavItems.add(new NavItem(getString(R.string.profile), "",
                R.mipmap.profile));
        listNavItems.add(new NavItem(getString(R.string.create_activity), "",
                R.mipmap.help));
        listNavItems.add(new NavItem(getString(R.string.activities), "",
                R.mipmap.myevent));
        listNavItems.add(new NavItem(getString(R.string.settings), "",
                R.mipmap.settings));
        listNavItems.add(new NavItem(getString(R.string.about), "",
                R.mipmap.info));
        listNavItems.add(new NavItem(getString(R.string.exit), " ",
                R.mipmap.exit));


        NavListAdapter navListAdapter = new NavListAdapter(
                getApplicationContext(), R.layout.item_nav_list, listNavItems);

        lvNav.setAdapter(navListAdapter);

        listFragments = new ArrayList<Fragment>();
        listFragments.add(new MyHome());
        listFragments.add(new MyProfile());
        listFragments.add(new MyActivity());
        listFragments.add(new FragmentCreate());
        listFragments.add(new MyAbout());
        listFragments.add(new MyAbout());


        // load first fragment as default:
        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager.beginTransaction()
                .replace(R.id.main_content, listFragments.get(0)).commit();

        setTitle(listNavItems.get(0).getTitle());
        lvNav.setItemChecked(0, true);

        drawerLayout.closeDrawer(drawerPane);

        // set listener for navigation items:
        lvNav.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {

                if(position==6)
                    logoutUser();
                else if(position==4){
                    Intent intent = new Intent(HomeActivity.this,SettingsActivity.class);
                    startActivity(intent);
                }

                else{

                    // replace the fragment with the selection correspondingly:
                    FragmentManager fragmentManager = getSupportFragmentManager();
                    fragmentManager
                            .beginTransaction()
                            .replace(R.id.main_content, listFragments.get(position))
                            .commit();


                    setTitle(listNavItems.get(position).getTitle());
                    lvNav.setItemChecked(position, true);
                    drawerLayout.closeDrawer(drawerPane);
                }

            }
        });

        HashMap<String, String> user = db.getUserDetails();

        String name = user.get("name");
        String email = user.get("email");
        username = user.get("username");
        txtName.getText();
        // Displaying the user details on the screen
        txtName.setText(name);
        //txtEmail.setText(email);
        // create listener for drawer layout
        actionBarDrawerToggle = new ActionBarDrawerToggle(this, drawerLayout,
                R.string.drawer_opened, R.string.drawer_closed) {

            @Override
            public void onDrawerOpened(View drawerView) {
                // TODO Auto-generated method stub
                invalidateOptionsMenu();
                super.onDrawerOpened(drawerView);
            }

            @Override
            public void onDrawerClosed(View drawerView) {
                // TODO Auto-generated method stub
                invalidateOptionsMenu();
                super.onDrawerClosed(drawerView);
            }

        };

        drawerLayout.setDrawerListener(actionBarDrawerToggle);

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.

        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the MyHome/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        if (actionBarDrawerToggle.onOptionsItemSelected(item))
            return true;

        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onPostCreate(savedInstanceState);
        actionBarDrawerToggle.syncState();
    }

    private void logoutUser() {
        session.setLogin(false);

        db.deleteUsers();

        // Launching the login activity
        Intent intent = new Intent(HomeActivity.this, LoginActivity.class);
        startActivity(intent);
        finish();
    }

}
