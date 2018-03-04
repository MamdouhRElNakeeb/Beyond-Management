//
//  RegisterTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 2/6/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire
import SideMenuController

class RegisterTVC: UITableViewController {

    @IBOutlet weak var fNameTF: UITextField!
    @IBOutlet weak var mNameTF: UITextField!
    @IBOutlet weak var lNameTF: UITextField!
    @IBOutlet weak var phoneTF: UITextField!
    @IBOutlet weak var strAddTF: UITextField!
    @IBOutlet weak var cityTF: UITextField!
    @IBOutlet weak var stateTF: UITextField!
    @IBOutlet weak var zipCodeTF: UITextField!
    @IBOutlet weak var countryTF: UITextField!
    @IBOutlet weak var emailTF: UITextField!
    @IBOutlet weak var passwordTF: UITextField!
    
    @IBOutlet weak var indicator: UIActivityIndicatorView!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Uncomment the following line to preserve selection between presentations
        // self.clearsSelectionOnViewWillAppear = false

        // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
        // self.navigationItem.rightBarButtonItem = self.editButtonItem
        UIApplication.shared.statusBarStyle = .default
        
        
    }

    @IBAction func backBtnOnClick(_ sender: Any) {
        dismiss(animated: true, completion: nil)
    }
    
    @IBAction func finishBtnOnClick(_ sender: Any) {
        
        let fName = fNameTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let mName = mNameTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let lName = lNameTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        let phone = phoneTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let strAddress = strAddTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let city = cityTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let state = stateTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let zipCode = zipCodeTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let country = countryTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        let email = emailTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        let password = passwordTF.text?.trimmingCharacters(in: CharacterSet.whitespacesAndNewlines)
        
        // Full Name
        if (fName?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter your First Name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing name")
            return
        }
        if (mName?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Middle Name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing name")
            return
        }
        if (lName?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Last Name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing name")
            return
        }
        
        if (phone?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Phone", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing phone")
            return
        }
        if (strAddress?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Street Address", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
            return
        }
        if (city?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your City", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
            return
        }
        if (state?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your State", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
            return
        }
        if (zipCode?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Zip Code", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
            return
        }
        if (country?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Country", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing address")
            return
        }
        
        if (email?.isEmpty)!{
            
            let alert = UIAlertController(title: "Error", message: "Please enter your Email", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing Email")
            return
        }
        if (password?.isEmpty)! {
            
            let alert = UIAlertController(title: "Error", message: "Please enter you Password", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            
            print("missing password")
            return
        }
        
        let params: Parameters = [
            "fName": fName!,
            "mName": mName!,
            "lName": lName!,
            
            "phone": phone!,
            "strAdd": strAddress!,
            "city": city!,
            "state": state!,
            "zipCode": zipCode!,
            "country": country!,
            
            "email": email!,
            "password": password!,
            "token": UserDefaults.standard.string(forKey: "deviceToken") ?? "0"
        ]
        
        print(params)
        
        indicator.startAnimating()
        Alamofire.request(Urls.REGISTER_USER, method: .post, parameters: params)
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
                        
                        let mainVC =  self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as! UINavigationController
                        
                        let sideMenuVC =  self.storyboard?.instantiateViewController(withIdentifier: "sideMenuVC") as! SideMenuVC
                        let sideMenuViewController = SideMenuController()
                        // embed the side and center controllers
                        sideMenuViewController.embed(sideViewController: sideMenuVC)
                        sideMenuViewController.embed(centerViewController: mainVC)
                        
                        self.show(sideMenuViewController, sender: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                        
                        return
                    }
                    
                }
                self.indicator.stopAnimating()
        }
        
    }
    
    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
        self.view.endEditing(true)
    }
    
    // MARK: - Table view data source

//    override func numberOfSections(in tableView: UITableView) -> Int {
//        // #warning Incomplete implementation, return the number of sections
//        return 3
//    }

    /*
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return 0
    }
    */
    
    /*
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "reuseIdentifier", for: indexPath)

        // Configure the cell...

        return cell
    }
    */

}
