//
//  AppDelegate.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/2/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Braintree
import Firebase
import UserNotifications

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {

    var window: UIWindow?


    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        // Override point for customization after application launch.
        
        BTAppSwitch.setReturnURLScheme("org.beyondmanagement.app.payments")
        FirebaseApp.configure()
//        fetchClientToken()
        
        if #available(iOS 10.0, *) {
            // For iOS 10 display notification (sent via APNS)
            UNUserNotificationCenter.current().delegate = self
            
            let authOptions: UNAuthorizationOptions = [.alert, .badge, .sound]
            UNUserNotificationCenter.current().requestAuthorization(
                options: authOptions,
                completionHandler: {_, _ in })
        } else {
            let settings: UIUserNotificationSettings =
                UIUserNotificationSettings(types: [.alert, .badge, .sound], categories: nil)
            application.registerUserNotificationSettings(settings)
        }
        
        UNUserNotificationCenter.current().delegate = self
        application.registerForRemoteNotifications()
        
        return true
    }
    
    
    
    func fetchClientToken() {
        // TODO: Switch this URL to your own authenticated API
        let clientTokenURL = NSURL(string: "http://bm.nakeeb.me/payments/create_token.php")!
        let clientTokenRequest = NSMutableURLRequest(url: clientTokenURL as URL)
        clientTokenRequest.setValue("text/plain", forHTTPHeaderField: "Accept")
        
        URLSession.shared.dataTask(with: clientTokenRequest as URLRequest) { (data, response, error) -> Void in
            // TODO: Handle errors
            let clientToken = String(data: data!, encoding: String.Encoding.utf8)
            let userDefaults = UserDefaults()
            userDefaults.set(clientToken, forKey: "clientToken")

            // As an example, you may wish to present Drop-in at this point.
            // Continue to the next section to learn more...
            }.resume()
    }
    
    func application(_ app: UIApplication, open url: URL, options: [UIApplicationOpenURLOptionsKey : Any]) -> Bool {
        if url.scheme?.localizedCaseInsensitiveCompare("org.beyondmanagement.app.payments") == .orderedSame {
            return BTAppSwitch.handleOpen(url, options: options)
        }
        return false
    }

    
    // [START receive_message]
    func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable: Any]) {
        // If you are receiving a notification message while your app is in the background,
        // this callback will not be fired till the user taps on the notification launching the application.
        // TODO: Handle data of notification
        // With swizzling disabled you must let Messaging know about the message, for Analytics
        // Messaging.messaging().appDidReceiveMessage(userInfo)
        // Print message ID.
        
        
        
        // Print full message.
        print("didReceiveRemoteNotificationn: \(userInfo)")
    }
    
    func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
        let userd = UserDefaults()
        /*
         let trimmedToken = deviceToken.description.replacingOccurrences(of: " ", with: "").replacingOccurrences(of: "<", with: "").replacingOccurrences(of: ">", with: "")
         userd.setValue(trimmedToken, forKey: "deviceToken")
         print("Did Register for Remote Notifications with Device Token (%@)", deviceToken)
         */
        let deviceTokenString = deviceToken.reduce("", {$0 + String(format: "%02X", $1)})
        userd.setValue(deviceTokenString, forKey: "deviceToken")
        
        print("newToken: \(deviceTokenString)")
        
        print(deviceTokenString)
    }
    
    func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable: Any], fetchCompletionHandler completionHandler: @escaping (UIBackgroundFetchResult) -> Void) {
        
        let center = UNUserNotificationCenter.current()
        
        print("didReceiveRemoteNotification: \(userInfo["aps"])")
        
        completionHandler(UIBackgroundFetchResult.newData)
        
        
        
        if let notification = userInfo["aps"] as? NSDictionary,
            let message = notification["message"] as? String {
            
            /*    let notification = UILocalNotification()
             notification.alertBody = notificationtext
             notification.alertAction = "open"
             notification.fireDate = Date(timeIntervalSinceNow: 1)
             notification.soundName = UILocalNotificationDefaultSoundName
             //notification.userInfo = message as? [String : AnyObject]
             //notification.category = message
             UIApplication.shared.scheduleLocalNotification(notification)
             */
            completionHandler(UIBackgroundFetchResult.newData)
            
            //if(isMessage(message) && application.applicationState == UIApplicationState.Active) {
            //    NSNotificationCenter.defaultCenter().postNotificationName("newChatMessage", object: message["entity_id"])
            //}
            
            let content = UNMutableNotificationContent()
            content.title = "Beyond Management"
            content.body = message
            content.sound = UNNotificationSound.default()
            let trigger = UNTimeIntervalNotificationTrigger(timeInterval: 1, repeats: false)
            
            let identifier = "UYLLocalNotification"
            let request = UNNotificationRequest(identifier: identifier,
                                                content: content, trigger: trigger)
            center.add(request, withCompletionHandler: { (error) in
                if let error = error {
                    // Something went wrong
                    print("notificationsErr: \(error)")
                }
            })
            
        }
        
    }
    
    func application(_ application: UIApplication, didFailToRegisterForRemoteNotificationsWithError error: Error) {
        print("Did Fail to Register for Remote Notifications")
        let userd = UserDefaults()
        
        userd.setValue("0", forKey: "deviceToken")
        print(error.localizedDescription)
    }
    
    func applicationDidBecomeActive(_ application: UIApplication) {
        // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
        print("App became active")
        application.applicationIconBadgeNumber = 0;
    }

}

// [START ios_10_message_handling]
@available(iOS 10, *)
extension AppDelegate : UNUserNotificationCenterDelegate {
    
    // Receive displayed notifications for iOS 10 devices.
    func userNotificationCenter(_ center: UNUserNotificationCenter,
                                willPresent notification: UNNotification,
                                withCompletionHandler completionHandler: @escaping (UNNotificationPresentationOptions) -> Void) {
        let userInfo = notification.request.content.userInfo
        
        // With swizzling disabled you must let Messaging know about the message, for Analytics
        // Messaging.messaging().appDidReceiveMessage(userInfo)
        // Print message ID.
        
        // Print full message.
        print("willPresentNoti: \(userInfo["aps"])")
        print("not: \(notification)")
        
        if let notification = userInfo["aps"] as? NSDictionary,
            let message = notification["message"] as? String {
            let notificationtext = message
            print(notificationtext)
            
            let content = UNMutableNotificationContent()
            content.title = "Beyond Management"
            content.body = notificationtext
            content.sound = UNNotificationSound.default()
            let trigger = UNTimeIntervalNotificationTrigger(timeInterval: 1, repeats: false)
            
            let request = UNNotificationRequest(identifier: UUID().uuidString, content: content, trigger: trigger)
            
            
            UNUserNotificationCenter.current().add(request) { (error:Error?) in
                
                if error != nil {
                    print(error ?? "notiErr")
                }
                print("Notification Register Success")
            }
        }
        
        // Change this to your preferred presentation option
        //completionHandler([])
        completionHandler([UNNotificationPresentationOptions.alert, UNNotificationPresentationOptions.badge, UNNotificationPresentationOptions.sound])
    }
    
    func userNotificationCenter(_ center: UNUserNotificationCenter,
                                didReceive response: UNNotificationResponse,
                                withCompletionHandler completionHandler: @escaping () -> Void) {
        let userInfo = response.notification.request.content.userInfo
        // Print message ID.
        
        // Print full message.
        print("didReciveResponse: \(userInfo)")
        
        completionHandler()
    }
}
// [END ios_10_message_handling]
