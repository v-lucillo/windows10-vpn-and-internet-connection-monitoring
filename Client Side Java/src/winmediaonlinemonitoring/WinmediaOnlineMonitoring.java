/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */
package winmediaonlinemonitoring;

/**
 *
 * @author pcuser
 */
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.Inet4Address;
import java.net.InetAddress;
import java.net.MalformedURLException;
import java.net.NetworkInterface;
import java.net.SocketException;
import java.net.URL;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Collections;
import java.util.Date;
import java.util.Enumeration;
import java.util.Timer;
import java.util.TimerTask;

public class WinmediaOnlineMonitoring {

    public static void main(String[] args) throws MalformedURLException, IOException {
        Timer timer = new Timer();
        timer.schedule(new TimerTask() {
            @Override
            public void run() {
                try {
                    String adapters = "";
                    Enumeration<NetworkInterface> nets = NetworkInterface.getNetworkInterfaces();
                    for (NetworkInterface netint : Collections.list(nets)){
                        adapters += displayInterfaceInformation(netint).replaceAll("\\s+","");
                    }
//                    System.out.println(adapters);
                    String timestamp = new SimpleDateFormat("yyyy.MM.dd.HH.mm.ss").format(new Date());
                    String call_sign = "DWCMFM";
                    BufferedReader reader;
                    String line;
                    StringBuilder responseContent = new StringBuilder();
                    adapters = URLEncoder.encode(adapters, "UTF-8");
                    URL url = new URL("https://domain.com/api/connection?call_sign=" + call_sign + "&timestamp=" + timestamp + "&adapters=" + adapters);
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestProperty("accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8");
                    connection.setRequestMethod("GET");
                    connection.setConnectTimeout(5000);// 5000 milliseconds = 5 seconds
                    connection.setReadTimeout(5000);
                    int status = connection.getResponseCode();
                    if (status >= 300) {
                        reader = new BufferedReader(new InputStreamReader(connection.getErrorStream()));
                        while ((line = reader.readLine()) != null) {
                            responseContent.append(line);
                        }
                        reader.close();
                    } else {
                        reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                        while ((line = reader.readLine()) != null) {
                            responseContent.append(line);
                        }
                        reader.close();
                    }
                    System.out.println(responseContent.toString());
                } catch (IOException e) {
                    
                }
            }
        }, 0, 20000);

    }
    
    public static String displayInterfaceInformation(NetworkInterface netint) throws SocketException {
        Enumeration<InetAddress> inetAddresses = netint.getInetAddresses();
        String adapters = "";
        for (InetAddress inetAddress : Collections.list(inetAddresses)) {
            if (inetAddress instanceof Inet4Address) {
                String display_name = netint.getDisplayName();
                String ip_address = inetAddress.getHostAddress();
                    adapters +=  display_name +"-"+ip_address+"--";
            }
        }
        return adapters;
     }
}
