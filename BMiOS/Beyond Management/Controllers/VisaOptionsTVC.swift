//
//  VisaOptionsTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/8/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire
import Braintree
import BraintreeDropIn

class VisaOptionsTVC: UITableViewController, BTAppSwitchDelegate, BTViewControllerPresentingDelegate {
    
    @IBOutlet weak var infoTV: UITextView!
    @IBOutlet weak var basicLbl: UILabel!
    @IBOutlet weak var basicInfoTV: UITextView!
    @IBOutlet weak var basicPayBtn: UIButton!
    @IBOutlet weak var interLbl: UILabel!
    @IBOutlet weak var interInfoTV: UITextView!
    @IBOutlet weak var interPayBtn: UIButton!
    @IBOutlet weak var advLbl: UILabel!
    @IBOutlet weak var advInfoTV: UITextView!
    @IBOutlet weak var advPayBtn: UIButton!
    
    var visa = Visa()
    var visaType = ""
    
    var expanded = Array<Int>()
    
    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
    
    
    var braintreeClient: BTAPIClient!
    
    var clientToken = ""
    
    override func viewDidLoad() {
        super.viewDidLoad()

        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        self.navigationItem.title = visa.name
        
//        indicator.startAnimating()
        
        infoTV.text = visa.info
        
        basicInfoTV.text = visa.basicInfo
        basicInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        basicPayBtn.setTitle("$\(visa.basicPrice)", for: .normal)
        basicPayBtn.layer.cornerRadius = 10
     
        interInfoTV.text = visa.interInfo
        interInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        interPayBtn.setTitle("$\(visa.interPrice)", for: .normal)
        interPayBtn.layer.cornerRadius = 10
        
        advInfoTV.text = visa.advInfo
        advInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        advPayBtn.setTitle("$\(visa.advPrice)", for: .normal)
        advPayBtn.layer.cornerRadius = 10
        
        fetchClientToken()
    }
    
    @IBAction func basicPayBtnOnClick(_ sender: UIButton) {
        visaType = "basic"
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visa.basicPrice)
    }
    
    @IBAction func interPayBtnOnClick(_ sender: UIButton) {
        visaType = "intermediate"
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visa.interPrice)
    }
    
    @IBAction func advPayBtnOnClick(_ sender: UIButton) {
        visaType = "advanced"
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visa.advPrice)
    }
    
    
    // Payment
    func showDropIn(clientTokenOrTokenizationKey: String, amount: Int) {
        
        indicator.startAnimating()
        
        let request =  BTDropInRequest()
        request.amount = "\(amount)"
        
        if clientTokenOrTokenizationKey.isEmpty {
            indicator.stopAnimating()
            return
        }
        
        let dropIn = BTDropInController(authorization: clientTokenOrTokenizationKey, request: request){
            (controller, result, error) in
            
            self.indicator.stopAnimating()
            
            if (error != nil) {
                print("ERROR")
            } else if (result?.isCancelled == true) {
                print("CANCELLED")
            } else if let result = result {
                // Use the BTDropInResult properties to update your UI
                // result.paymentOptionType
                // result.paymentMethod
                // result.paymentIcon
                // result.paymentDescription
                
                print(result.paymentMethod?.nonce ?? "")
                
                self.postNonceToServerVault((result.paymentMethod?.nonce ?? ""), amount)
            }
            controller.dismiss(animated: true, completion: nil)
        }
        
        self.present(dropIn!, animated: true, completion: nil)
    }
    
    func postNonceToServerVault(_ paymentMethodNonce: String, _ amount: Int) {
        
        indicator.startAnimating()
        //let dataCollector = BTDataCollector(environment: .Sandbox)
        //let deviceData = dataCollector.collectCardFraudData()
        
        let deviceData = PPDataCollector.collectPayPalDeviceData()
        let customerId = UserDefaults().string(forKey: "customerId")!
        print("postNonceToServerVault deviceData: \(deviceData)")
        
        let params: Parameters = [
            "payment_method_nonce": paymentMethodNonce,
            "customerId": customerId,
            "amount": "\(amount)",
            "visaName": visa.name,
            "visaType": visaType
        ]
        
        print(params)
        
        Alamofire.request(Urls.PAY_NONCE_VAULT, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let json = result as! NSDictionary
                
                if let success = json.value(forKey: "paySuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Thank you, Your request have been submitted.\n You will receive a notification to complete the required documents.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "payError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                else if let success = json.value(forKey: "methodSuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Your payment method have been added.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "methodError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                
                
            }
            
            self.indicator.stopAnimating()
        }
        
    }
    
    
    func fetchClientToken(){
        
        let parameters: Parameters = [
            "customerId": UserDefaults().string(forKey: "customerId")!
        ]
        
        print(parameters)
        
        Alamofire.request(Urls.CLIENT_TOKEN, method: .post, parameters: parameters)
            .responseJSON{
                
                response in
                
                print(response)
                
                if let result = response.result.value {
                    
                    let json = result as! NSDictionary
                    self.clientToken = json.value(forKey: "token") as! String
                    
                }
        }
        
    }
    
    // MARK: - BTViewControllerPresentingDelegate
    func paymentDriver(_ driver: Any, requestsDismissalOf viewController: UIViewController) {
        dismiss(animated: true, completion: nil)
    }
    
    func paymentDriver(_ driver: Any, requestsPresentationOf viewController: UIViewController) {
        present(viewController, animated: true, completion: nil)
    }
    
    // MARK: - BTAppSwitchDelegate
    func appSwitcherWillPerformAppSwitch(_ appSwitcher: Any) {
        
    }
    
    func appSwitcher(_ appSwitcher: Any, didPerformSwitchTo target: BTAppSwitchTarget) {
        
    }
    
    func appSwitcherWillProcessPaymentInfo(_ appSwitcher: Any) {
        
    }
    
    
    // MARK: - Table view data source

    override func numberOfSections(in tableView: UITableView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }

    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return 4
    }

    
    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        
        switch indexPath.row {
    
        case 1:
            if expanded.contains(where: {$0 == indexPath.row}){
                basicInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                basicLbl.text = "↓ Basic"
                return 150
            }
            else {
                basicInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                basicLbl.text = "→ Basic"
                return 46
            }
        case 2:
            if expanded.contains(where: {$0 == indexPath.row}){
                interInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                interLbl.text = "↓ Intermediate"
                return 150
            }
            else {
                interInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                interLbl.text = "→ Intermediate"
                return 46
            }
        case 3:
            if expanded.contains(where: {$0 == indexPath.row}){
                advInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                advLbl.text = "↓ Advanced"
                return 150
            }
            else {
                advInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                advLbl.text = "→ Advanced"
                return 46
            }
        default:
            return 150
        }
        
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
    
        if indexPath.row != 0 {
           
            if expanded.contains(where: {$0 == indexPath.row}){
                // it exists
                expanded.remove(at: expanded.index(of: indexPath.row)!)
            }
            else{
                //item could not be found
                expanded.append(indexPath.row)
            }
            
            self.tableView.reloadData()
            print(expanded)
            
        }
    }
    
   
}




