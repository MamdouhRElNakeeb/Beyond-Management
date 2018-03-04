//
//  CardDetailsTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 2/26/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit

class CardDetailsTVC: UITableViewController, UITextViewDelegate {

    
    @IBOutlet weak var cardHolderNameTF: UITextField!
    @IBOutlet weak var cvvTF: UITextField!
    
    @IBOutlet weak var billingAddSwitch: UISwitch!
    @IBOutlet weak var strAddTF: UITextField!
    @IBOutlet weak var cityTF: UITextField!
    @IBOutlet weak var stateTF: UITextField!
    @IBOutlet weak var zipCodeTF: UITextField!
    @IBOutlet weak var countryTF: UITextField!
    
    @IBOutlet weak var seekForTV: UITextView!
    
    var cardDetailsDelegate: CardDetailsDelegate?
    
    
    @IBOutlet weak var indicator: UIActivityIndicatorView!
    
    var skype = false
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Uncomment the following line to preserve selection between presentations
        // self.clearsSelectionOnViewWillAppear = false

        // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
        // self.navigationItem.rightBarButtonItem = self.editButtonItem
        
        if !skype{
         
            seekForTV.addBorder(view: seekForTV, stroke: UIColor.greyMidColor(), fill: UIColor.clear, radius: 10, width: 2)
            seekForTV.delegate = self
            
        }
        
        setDefaultAddress()
    }
    
    @IBAction func cancelOnClick(_ sender: Any) {
        dismiss(animated: true, completion: nil)
    }
    
    @IBAction func onAddressSwitch(_ sender: Any) {
    
        if billingAddSwitch.isOn{
            setDefaultAddress()
        }
        else{
            setNewAddress()
        }
        
    }
    
    @IBAction func finishOnClick(_ sender: Any) {
        if (cardHolderNameTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter Card Holder Name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing card holder name")
            return
        }
        if (cvvTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter Card Security Code (CVV)", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing card holder name")
            return
        }
        
        if (strAddTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter the street address", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        if (cityTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter the city", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        if (stateTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter the state", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        if (zipCodeTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter the zip code", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        if (countryTF.text?.isEmpty)!{
            let alert = UIAlertController(title: "Error", message: "Please enter the country", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        
        if !skype && (seekForTV.text.isEmpty || seekForTV.text == "What are you seeking for?"){
            let alert = UIAlertController(title: "Error", message: "Please say what are you seeking for", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing seeking for")
            return
        }
        
        if skype {
            
            cardDetailsDelegate?.cardDetails(name: cardHolderNameTF.text!, cvv: cvvTF.text!, strAddress: strAddTF.text!, city: cityTF.text!, state: stateTF.text!, zipCode: zipCodeTF.text!, country: countryTF.text!)
            
        }
        else{
         
            cardDetailsDelegate?.cardDetailsWithSeek(name: cardHolderNameTF.text!, cvv: cvvTF.text!, strAddress: strAddTF.text!, city: cityTF.text!, state: stateTF.text!, zipCode: zipCodeTF.text!, country: countryTF.text!, seekFor: seekForTV.text)
            
        }
    }
    
    func textViewDidBeginEditing(_ textView: UITextView) {
        
        if textView == seekForTV{
            if textView.text == "What are you seeking for?"{
                textView.text = ""
                textView.textColor = UIColor.black
            }
        }
        
    }
    
    func textViewDidEndEditing(_ textView: UITextView) {
        
        if textView == seekForTV{
            if textView.text == ""{
                textView.text = "What are you seeking for?"
                textView.textColor = UIColor.greyMidColor()
            }
        }
    }
    
    func setDefaultAddress(){
    
        strAddTF.text = UserDefaults.standard.string(forKey: "strAdd")
        cityTF.text = UserDefaults.standard.string(forKey: "city")
        stateTF.text = UserDefaults.standard.string(forKey: "state")
        zipCodeTF.text = UserDefaults.standard.string(forKey: "zipCode")
        countryTF.text = UserDefaults.standard.string(forKey: "country")
        
        strAddTF.isEnabled = false
        cityTF.isEnabled = false
        stateTF.isEnabled = false
        zipCodeTF.isEnabled = false
        countryTF.isEnabled = false
        
    }
    
    func setNewAddress(){
        
        strAddTF.text = ""
        cityTF.text = ""
        stateTF.text = ""
        zipCodeTF.text = ""
        countryTF.text = ""
        
        strAddTF.isEnabled = true
        cityTF.isEnabled = true
        stateTF.isEnabled = true
        zipCodeTF.isEnabled = true
        countryTF.isEnabled = true
        
    }
    
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        if skype{
            return 2
        }
        else{
            return 3
        }
    }
    
}


protocol CardDetailsDelegate: class {
    
    func cardDetailsWithSeek(name: String, cvv: String, strAddress: String, city: String, state: String, zipCode: String, country: String, seekFor: String)
    
    func cardDetails(name: String, cvv: String, strAddress: String, city: String, state: String, zipCode: String, country: String)
}
