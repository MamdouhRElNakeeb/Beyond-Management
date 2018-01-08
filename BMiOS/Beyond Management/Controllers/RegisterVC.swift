//
//  RegisterVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire

class RegisterVC: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var nameTF: UITextField!
    @IBOutlet weak var emailTF: UITextField!
    @IBOutlet weak var phoneTF: UITextField!
    @IBOutlet weak var addressTF: UITextField!
    @IBOutlet weak var passwordTF: UITextField!
    @IBOutlet weak var signupBtn: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        
        initView()
    }
    
    func initView(){
        
        signupBtn.layer.cornerRadius = signupBtn.frame.height / 2
    }
    
    @IBAction func signupBtnClick(_ sender: UIButton) {
        
        let name = nameTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let email = emailTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let phone = phoneTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let address = addressTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let password = passwordTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)

        if (name?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter you name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing name")
            return
        }
        if (email?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter you Email", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing Email")
            return
        }
        if (phone?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter you phone", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing phone")
            return
        }
        if (address?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter you address", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
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
            "name": name!,
            "email": email!,
            "phone": phone!,
            "address": address!,
            "password": password!,
            "token": UserDefaults.standard.string(forKey: "deviceToken") ?? "0"
        ]
        
        print(params)
        
        Alamofire.request(Urls.REGISTER_USER, method: .post, parameters: params)
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
                        
                        let vc = self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as? UINavigationController
                        self.present(vc!, animated: true, completion: nil)
                        
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
    
    @IBAction func linkToLoginClick(_ sender: UIButton) {
        let loginVC = self.storyboard?.instantiateViewController(withIdentifier: "loginVC") as? LoginVC
        self.present(loginVC!, animated: true, completion: nil)
    }

    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
        self.view.endEditing(true)
    }
    
}
