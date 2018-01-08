//
//  ApplicationsTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/20/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire

class ApplicationsTVC: UITableViewController {

    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
    
    var appsArr = [ImmigrationApplication]()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Uncomment the following line to preserve selection between presentations
        // self.clearsSelectionOnViewWillAppear = false

        // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
        // self.navigationItem.rightBarButtonItem = self.editButtonItem
        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        
        getApps()
    }

    func getApps (){
        
        let params: Parameters = [
            "applicant_id": UserDefaults.standard.integer(forKey: "id")
        ]
        
        Alamofire.request(Urls.USER_APPS, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let jsonArr = result as! NSArray
                
                for i in 0 ..< jsonArr.count {
                    let jsonObj = jsonArr.object(at: i) as! NSDictionary
                    self.appsArr.append(ImmigrationApplication.init(id: Int(jsonObj.value(forKey: "id") as! String)!,
                                                  visa: jsonObj.value(forKey: "name") as! String,
                                                  type: jsonObj.value(forKey: "type") as! String,
                                                  status: jsonObj.value(forKey: "status") as! String,
                                                  img: jsonObj.value(forKey: "img") as! String))
                }
                
                self.tableView.reloadData()
            }
        }
    }
    
    // MARK: - Table view data source

    override func numberOfSections(in tableView: UITableView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }
    
    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 150
    }

    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return appsArr.count
    }

    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "visaCell", for: indexPath) as! VisaServicesTVCell
        
        cell.layoutIfNeeded()
        
        // Configure the cell...
        let app = appsArr[indexPath.row]
        
        
        let attributedAppName = NSMutableAttributedString(string: app.visa, attributes: [NSAttributedStringKey.font : UIFont.systemFont(ofSize: 35, weight: UIFont.Weight.heavy), NSAttributedStringKey.foregroundColor : UIColor.white])
        

        let attributedAppType = NSMutableAttributedString(string: app.type, attributes: [NSAttributedStringKey.font : UIFont.systemFont(ofSize: 20, weight: UIFont.Weight.regular), NSAttributedStringKey.foregroundColor : UIColor.white])
        
        let attributedAppStatus = NSMutableAttributedString(string: app.status, attributes: [NSAttributedStringKey.font : UIFont.systemFont(ofSize: 15, weight: UIFont.Weight.ultraLight), NSAttributedStringKey.foregroundColor : UIColor.white])
        
        attributedAppName.append(NSMutableAttributedString(string: "\n \n"))
        attributedAppName.append(attributedAppType)
        attributedAppName.append(NSMutableAttributedString(string: "\n \n"))
        attributedAppName.append(attributedAppStatus)
        
        
        cell.visaLbl.attributedText = attributedAppName
        cell.visaIV.sd_setImage(with: URL(string: Urls.VISA_IMGS + appsArr[indexPath.row].img), placeholderImage: UIImage(named: "visa"))
        
        return cell
    }
    
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        tableView.deselectRow(at: indexPath, animated: true)
        
        let vc = self.storyboard?.instantiateViewController(withIdentifier: "RequirementsTVC") as? RequirementsTVC
        vc?.app = appsArr[indexPath.row]
        self.navigationController?.pushViewController(vc!, animated: true)
    }


}
