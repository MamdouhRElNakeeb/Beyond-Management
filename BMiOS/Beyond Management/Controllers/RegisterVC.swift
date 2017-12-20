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

        if (name?.isEmpty)! || (email?.isEmpty)! || (phone?.isEmpty)! || (address?.isEmpty)! || (password?.isEmpty)! {
            print("missing fields")
            return
        }
        
        let params: Parameters = [
            "name": name!,
            "email": email!,
            "phone": phone!,
            "address": address!,
            "password": password!
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
