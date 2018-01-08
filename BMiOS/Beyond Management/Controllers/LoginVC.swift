//
//  ViewController.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/2/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire

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
        let vc = self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as? UINavigationController
        self.present(vc!, animated: true, completion: nil)

        
//        let newViewController = UIStoryboard(name: "Main", bundle: nil).instantiateViewController(withIdentifier: "homeNC")
//        UIApplication.topViewController()?.present(newViewController, animated: true, completion: nil)

    }
    
    func login(){
        
        let email = emailTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let password = passwordTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        if (email?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter you Email", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing email")
            return
        }
        
        if (password?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter you password", preferredStyle: UIAlertControllerStyle.alert)
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
                        userDefaults.set(json.value(forKey: "name") as! String, forKey: "name")
                        userDefaults.set(json.value(forKey: "email") as! String, forKey: "email")
                        userDefaults.set(json.value(forKey: "phone") as! String, forKey: "phone")
                        userDefaults.set(json.value(forKey: "address") as! String, forKey: "address")
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
    
    @IBAction func linkToRegClick(_ sender: UIButton) {
        
        let registerVC = self.storyboard?.instantiateViewController(withIdentifier: "registerVC") as? RegisterVC
        self.present(registerVC!, animated: true, completion: nil)
    }
    
    
}

