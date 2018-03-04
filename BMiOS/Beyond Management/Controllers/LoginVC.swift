//
//  ViewController.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/2/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire
import SideMenuController

class LoginVC: UIViewController {

    @IBOutlet weak var loginBtn: UIButton!
    @IBOutlet weak var emailTF: UITextField!
    @IBOutlet weak var passwordTF: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        print(UserDefaults().bool(forKey: "login"))
        
        initView()
        
    }
    
    func initView(){
        
        loginBtn.layer.cornerRadius = loginBtn.frame.height / 2
    }
    
    func goHome(){
//        let vc = self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as? UINavigationController
//        self.present(vc!, animated: true, completion: nil)
        let mainVC =  self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as! UINavigationController
        //            self.present(mainVC, animated: true, completion: nil)
        
        let sideMenuVC =  self.storyboard?.instantiateViewController(withIdentifier: "sideMenuVC") as! SideMenuVC
        let sideMenuViewController = SideMenuController()
        // embed the side and center controllers
        sideMenuViewController.embed(sideViewController: sideMenuVC)
        sideMenuViewController.embed(centerViewController: mainVC)
        
        show(sideMenuViewController, sender: nil)
        
//        let newViewController = UIStoryboard(name: "Main", bundle: nil).instantiateViewController(withIdentifier: "homeNC")
//        UIApplication.topViewController()?.present(newViewController, animated: true, completion: nil)

    }
    
    func login(){
        
        let email = emailTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let password = passwordTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        if (email?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Email", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing email")
            return
        }
        
        if (password?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Password", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing password")
            return
        }
        
        let params: Parameters = [
            "email": email!,
            "password": password!,
            "token": UserDefaults.standard.string(forKey: "deviceToken") ?? "0"
        ]
        
        print(params)
        
        Alamofire.request(Urls.LOGIN_USER, method: .post, parameters: params)
            .responseJSON{
                
                response in
                
                print(response)
                
                if let result = response.result.value {
                    
                    let json = result as! NSDictionary
                    
                    if !(json.value(forKey: "error") as! Bool) {
                        
                        let userDefaults = UserDefaults()
                        userDefaults.set(json.value(forKey: "id") as! String, forKey: "id")
                        userDefaults.set(json.value(forKey: "fname") as! String, forKey: "fname")
                        userDefaults.set(json.value(forKey: "mname") as! String, forKey: "mname")
                        userDefaults.set(json.value(forKey: "lname") as! String, forKey: "lname")
                        
                        userDefaults.set(json.value(forKey: "phone") as! String, forKey: "phone")
                        userDefaults.set(json.value(forKey: "strAdd") as! String, forKey: "strAdd")
                        userDefaults.set(json.value(forKey: "city") as! String, forKey: "city")
                        userDefaults.set(json.value(forKey: "state") as! String, forKey: "state")
                        userDefaults.set(json.value(forKey: "zipCode") as! String, forKey: "zipCode")
                        userDefaults.set(json.value(forKey: "country") as! String, forKey: "country")
                        
                        userDefaults.set(json.value(forKey: "email") as! String, forKey: "email")
                        userDefaults.set(json.value(forKey: "customer_id") as! String, forKey: "customerId")
                        userDefaults.set(true, forKey: "login")
                        userDefaults.synchronize()                        
                        
                        self.goHome()
                        
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                        
                        return
                    }
                    
                }
                
        }
        
    }
    
    @IBAction func loginBtnClick(_ sender: UIButton) {
        
        login()
        
    }
    
    @IBAction func forgotPassOnClick(_ sender: Any) {
        
        let email = emailTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        if (email?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Email", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing email")
            return
        }
        
       
        let params: Parameters = [
            "email": email!
        ]
        
        print(params)
        
        Alamofire.request(Urls.PASS_RESET, method: .post, parameters: params)
            .responseJSON{
                
                response in
                
                print(response)
                
                if let result = response.result.value {
                    
                    let json = result as! NSDictionary
                    
                    if !(json.value(forKey: "error") as! Bool) {
                        
                        let alert = UIAlertController(title: "Success", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Ok", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                        
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                        
                        return
                    }
                    
                }
                
        }
        
    }
    
    @IBAction func linkToRegClick(_ sender: UIButton) {
        
        let registerVC = self.storyboard?.instantiateViewController(withIdentifier: "RegisterTVC") as? RegisterTVC
        self.present(registerVC!, animated: true, completion: nil)
    }
    
    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
        self.view.endEditing(true)
    }
    
}

