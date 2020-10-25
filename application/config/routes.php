<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*login route*/
//$route['(:any)/login'] = 'AuthController/login';
$route['(:any)/login'] = 'AuthController/index';
$route['(:any)'] = 'AuthController/index';
$route['default_controller'] = 'AuthController';
$route['(:any)/signout'] = 'AuthController/signout';


/*dashbord route*/
$route['(:any)/moduleDashboard'] = 'lpg/HomeController/moduleDashboard';
$route['(:any)/insert_menu_accessList'] = 'lpg/HomeController/insert_menu_accessList';
$route['(:any)/insert_menu_accessListByUserRole'] = 'lpg/HomeController/insert_menu_accessListByUserRole';
$route['(:any)/cus_credit_due_List'] = 'lpg/CustomerCreditDueListController/cus_credit_due_List';


/* for dropdown of product list by product Category*/
//$route['(:any)/getProductList'] = 'lpg/InvProductController/getProductList';


/*product rice for sales*/
//$route['(:any)/getProductPriceForSale'] = 'lpg/InvProductController/getProductPriceForSale';
//$route['(:any)/getProductPriceForPurchase'] = 'lpg/InvProductController/getProductPriceForPurchase';


/*product stock quantity in sales invoice*/
//$route['(:any)/getProductStock'] = 'lpg/InvProductController/getProductStock';

/*sales route
 *
 *
 *
 * */

$route['(:any)/salesInvoice_add'] = 'lpg/SalesController/salesInvoice_add';
$route['(:any)/reference'] = 'lpg/SalesController/reference';
$route['(:any)/referenceAdd'] = 'lpg/SalesController/referenceAdd';

//$route['(:any)/deleteReference/(:any)'] = 'lpg/SalesController/deleteReference/$2';
$route['(:any)/editReference/(:any)'] = 'lpg/SalesController/editReference/$2';
$route['(:any)/salesInvoice'] = 'lpg/SalesController/salesInvoice';
//$route['(:any)/server_filter_sales_list'] = 'lpg/ServerFilterController/salesList';
$route['(:any)/salesInvoice_view/(:any)'] = 'lpg/SalesController/salesInvoice_view/$2';
$route['(:any)/getCustomerCurrentBalance'] = 'lpg/SalesController/getCustomerCurrentBalance';
$route['(:any)/customerPayment'] = 'lpg/SalesController/customerPayment';
//$route['(:any)/cusPayListServerFilter'] = 'lpg/ServerFilterController/cusPayList';
$route['(:any)/customerPaymentAdd'] = 'customerPaymentController/customerPaymentAdd';
$route['(:any)/viewMoneryReceipt/(:any)'] = 'lpg/SalesController/viewMoneryReceipt/$2';

//$route['(:any)/getbankbranchList'] = 'lpg/SalesController/getbankbranchList';

//$route['(:any)/customer_ajax_rourt'] = 'lpg/SalesController/customer_ajax';


/*sales route*/

/*purchase route
 *
 *
 *
 * */


$route['(:any)/purchases_list'] = 'lpg/PurchaseController/purchases_list';
$route['(:any)/purchases_add'] = 'lpg/PurchaseController/purchases_add';
$route['(:any)/purchases_edit/(:any)'] = 'lpg/PurchaseController/purchases_edit/$2';
//$route['(:any)/server_filter_purchase_list'] = 'lpg/ServerFilterController/purchasesList';
$route['(:any)/viewPurchases/(:any)'] = 'lpg/PurchaseController/viewPurchases/$2';
//$route['(:any)/ServerFilterSupPayList'] = 'lpg/ServerFilterController/supPayList';
$route['(:any)/supplierPaymentAdd'] = 'lpg/InventoryController/supplierPaymentAdd';
$route['(:any)/supPendingCheque'] = 'lpg/PurchaseLpgController/supPendingCheque';


$route['(:any)/addProduct'] = 'lpg/InvProductController/addProduct';
$route['(:any)/updateProduct/(:any)'] = 'lpg/InvProductController/updateProduct/$2';
$route['(:any)/productStatusChange'] = 'lpg/InvProductController/productStatusChange';
//$route['(:any)/deleteProduct/(:any)'] = 'lpg/InventoryController/deleteProduct/$2';
//$route['(:any)/checkDuplicateProduct'] = 'lpg/InvProductController/checkDuplicateProduct';
$route['(:any)/productList'] = 'lpg/InvProductController/productList';
//$route['(:any)/productListServerFilter'] = 'lpg/ServerFilterController/productList';




/*Product Package */
$route['(:any)/productPackageList']='lpg/ProductPackageController/product_package_list';
$route['(:any)/productPackageAdd']='lpg/ProductPackageController/product_package_add';
//$route['(:any)/ServerFilter_productPackageList']='lpg/ServerFilterController/productPackageList';
$route['(:any)/productPackageEdit/(:any)']='lpg/ProductPackageController/product_package_edit/$2';
$route['(:any)/productPackageView/(:any)']='lpg/ProductPackageController/product_package_view/$2';
//$route['(:any)/productPackageDelete/(:any)']='lpg/ProductPackageController/product_package_delete/$2';




/*supplier controller*/
$route['(:any)/supplierList'] = 'lpg/SupplierController/supplierList';
//$route['(:any)/ServerFilter_supplierList']='lpg/ServerFilterController/supplierList';
$route['(:any)/Supplier'] = 'lpg/SupplierController/Supplier';
//$route['(:any)/checkDuplicateEmailSupplier'] = 'lpg/SupplierController/checkDuplicateEmail';
$route['(:any)/suplierStatusChange'] = 'lpg/SupplierController/suplierStatusChange';
$route['(:any)/supplierUpdate/(:any)'] = 'lpg/SupplierController/supplierUpdate/$2';

/*supplier controller*/

/*supplier controller*/
$route['(:any)/addProductCat'] = 'lpg/ProductCategoryController/addProductCat';
$route['(:any)/productCatList'] = 'lpg/ProductCategoryController/productCatList';
//$route['(:any)/ServerFilter_productCatList']='lpg/ServerFilterController/productCatList';
$route['(:any)/updateProductCat/(:any)'] = 'lpg/ProductCategoryController/updateProductCat/$2';
//$route['(:any)/deleteProductCategory/(:any)'] = 'lpg/ProductCategoryController/deleteProductCategory/$2';


/*supplier controller*/


/*brand controller*/
$route['(:any)/brand'] = 'lpg/BrandController/brand';
$route['(:any)/brandAdd'] = 'lpg/BrandController/brandAdd';
//$route['(:any)/ServerFilter_brandList']='lpg/ServerFilterController/brandList';
$route['(:any)/brandEdit/(:any)'] = 'lpg/BrandController/brandEdit/$2';
//$route['(:any)/deleteBrand/(:any)'] = 'lpg/BrandController/deleteBrand/$2';

$route['(:any)/unit'] = 'lpg/UnitController/unit';
//$route['(:any)/ServerFilter_unitList']='lpg/ServerFilterController/unitList';
$route['(:any)/unitAdd'] = 'lpg/UnitController/unitAdd';
$route['(:any)/unitEdit/(:any)'] = 'lpg/UnitController/unitEdit/$2';
$route['(:any)/deleteUnit/(:any)'] = 'lpg/UnitController/deleteUnit/$2';


/*product barcode controller
 *
 *
 *
 *
 *
 * */
//$route['(:any)/productBarcode'] = 'lpg/InvProductController/productBarcode';
$route['(:any)/productBarcode'] = 'lpg/ProductBarcodeController/productBarcode';

//$route['(:any)/getProductListForBarcode'] = 'lpg/InvProductController/getProductListForBarcode';

/*product barcode end*/


/*product type controller
 *
 *
 *
 *
 *
 * */

$route['(:any)/productType'] = 'lpg/InvProductController/productTypeList';
//$route['(:any)/productTypeStatusChange'] = 'lpg/InvProductController/productTypeStatusChange';
$route['(:any)/addproductType'] = 'lpg/InvProductController/productType';





$route['(:any)/checkDuplicateProductType'] = 'lpg/InvProductController/checkDuplicateProductType';
$route['(:any)/ServerFilter_productTypeList']='lpg/ServerFilterController/productTypeList';
$route['(:any)/editproductType/(:any)'] = 'lpg/InvProductController/editProductType/$2';
/*product type controller*/


$route['(:any)/customerList'] = 'lpg/CustomerController/customerList';
$route['(:any)/addCustomer'] = 'lpg/CustomerController/addCustomer';
//$route['(:any)/customerDelete'] = 'lpg/CustomerController/customerDelete';
$route['(:any)/editCustomer/(:any)'] = 'lpg/CustomerController/editCustomer/$2';
//$route['(:any)/ServerFilter_customerList']='lpg/ServerFilterController/customerList';
//$route['(:any)/checkDuplicatePhone'] = 'lpg/CustomerController/checkDuplicatePhone';
//$route['(:any)/customerStatusChange'] = 'lpg/CustomerController/customerStatusChange';



/* Cylinder Exchange controller
 *
 *
 *
 *
 *
 * */
$route['(:any)/cylinderInOutJournalAdd'] = 'lpg/CylExchangeController/cylinderInOutJournalAdd';
$route['(:any)/cylinderInOutJournal'] = 'lpg/CylExchangeController/cylinderInOutJournal';
$route['(:any)/cylinderInOutJournalView/(:any)'] = 'lpg/CylExchangeController/cylinderInOutJournalView/$2';
$route['(:any)/cylinderInOutJournalEdit/(:any)'] = 'lpg/CylExchangeController/cylinderInOutJournalEdit/$2';


/* setup controller start */

$route['(:any)/userMessageList'] = 'lpg/SetupController/userMessageList';
$route['(:any)/userAllOfferList'] = 'lpg/SetupController/userAllOfferList';
$route['(:any)/getAllMessage'] = 'lpg/SetupController/getAllMessage';
$route['(:any)/incentiveList'] = 'lpg/SetupController/incentiveList';
$route['(:any)/userList'] = 'lpg/UserController/userList';
$route['(:any)/addUser'] = 'lpg/UserController/addUser';
$route['(:any)/editUser/(:any)'] = 'lpg/SetupController/editUser/$2';
$route['(:any)/decision'] = 'lpg/SetupController/decision_tools';
$route['(:any)/save_decision'] = 'lpg/SetupController/save_decision_tools';
$route['(:any)/compare_decision'] = 'lpg/SetupController/compare_decision';
$route['(:any)/distributor_profile'] = 'lpg/SetupController/distributor_profile';
$route['(:any)/updatePassword/(:any)'] = 'lpg/SetupController/updatePassword/$2';
$route['(:any)/change_password'] = 'lpg/SetupController/change_password';
$route['(:any)/newDecision'] = 'lpg/SetupController/newDecision';
$route['(:any)/newDecisionList'] = 'lpg/SetupController/newDecisionList';
$route['(:any)/SystemConfig'] = 'lpg/SetupController/SystemConfig';
$route['(:any)/purchase_order_config'] = 'lpg/SetupController/purchase_order_config';
$route['(:any)/sales_order_config'] = 'lpg/SetupController/sales_order_config';

//vehicle
$route['(:any)/vehicleList'] = 'lpg/SetupController/vehicleList';
$route['(:any)/vehicleAdd'] = 'lpg/SetupController/vehicleAdd';
$route['(:any)/vehicleEdit/(:any)'] = 'lpg/SetupController/vehicleEdit/$2)';
$route['(:any)/vehicleDelete/(:any)'] = 'lpg/SetupController/vehicleDelete/$2)';
//employee
$route['(:any)/employeeList'] = 'lpg/SetupController/employeeList';
$route['(:any)/employeeAdd'] = 'lpg/SetupController/employeeAdd';
$route['(:any)/employeeEdit/(:any)'] = 'lpg/SetupController/employeeEdit/$2)';
$route['(:any)/employeeDelete/(:any)'] = 'lpg/SetupController/employeeDelete/$2)';

/* setup controller end */


/* inventory  controller start */
$route['(:any)/stock_group_report'] = 'lpg/InvReportController/cylinder_stock_group_report';

$route['(:any)/supplierPayment'] = 'lpg/InventoryController/supplierPayment';

$route['(:any)/current_stock_report'] = 'lpg/InventoryReportController/current_stock_report';

//$route['(:any)/current_stock_value'] = 'lpg/InventoryReportController/current_stock_report';

$route['(:any)/current_stock_value'] = 'lpg/InventoryStockReportController/current_stock_report_with_branch';

/*$route['(:any)/current_stock_value'] = 'lpg/PurchaseController/current_stock_value';*/
$route['(:any)/current_stock_value2'] = 'lpg/PurchaseController/current_stock_value2';
$route['(:any)/stockReport'] = 'lpg/InventoryReportController/stockReport';
$route['(:any)/lowInventoryReport'] = 'lpg/InventoryController/lowInventoryReport';

$route['(:any)/supplierPurchasesReport'] = 'lpg/InventoryReportController/supplierPurchasesReport';

$route['(:any)/newCylinderStockReport'] = 'lpg/InventoryController/newCylinderStockReport';
$route['(:any)/cylinderStockReport'] = 'lpg/InventoryController/cylinderStockReport';
$route['(:any)/productWiseCylinderStock'] = 'lpg/InventoryController/productWiseCylinderStock';

$route['(:any)/cylinderLedger'] = 'lpg/InventoryController/cylinderLedger';
$route['(:any)/cylinderSummaryReport'] = 'lpg/InventoryController/cylinderSummaryReport';
//$route['(:any)/cylinderSummaryReport'] = 'lpg/InventoryController/cylinderSummaryReport';

$route['(:any)/cylinderDetailsReport'] = 'lpg/InventoryController/cylinderDetailsReport';

//$route['(:any)/full_cylinder_stock_report'] = 'lpg/InventoryController/cylinder_stock_report';
$route['(:any)/full_cylinder_stock_report'] = 'lpg/InventoryController/full_cylinder_stock_report';

$route['(:any)/cylinderOpening'] = 'lpg/InventoryController/cylinderOpening';
$route['(:any)/cylinderOpeningAdd'] = 'lpg/InventoryController/cylinderOpeningAdd';
$route['(:any)/cylinderOpeningView/(:any)'] = 'lpg/InventoryController/cylinderOpeningView/$2';


/*InventoryReportController*/
$route['(:any)/current_stock'] = 'lpg/InventoryReportController/current_stock';
$route['(:any)/productLedger'] = 'lpg/InventoryReportController/productLedger';
$route['(:any)/purchasesReport'] = 'lpg/InventoryReportController/purchasesReport';
$route['(:any)/productWisePurchasesReport'] = 'lpg/InventoryReportController/productWisePurchasesReport';
/*InventoryReportController*/

/*SalesReportController*/
$route['(:any)/date_wise_product_sales']='lpg/SalesReportController/date_wise_product_sales';
$route['(:any)/brandWiseProfit'] = 'lpg/SalesReportController/brandWiseProfit';
$route['(:any)/date_wise_product_sales_print']='lpg/SalesReportController/date_wise_product_sales_print';
$route['(:any)/daily_sales_statement']='lpg/SalesReportController/daily_sales_statement';
$route['(:any)/salesReport'] = 'lpg/SalesReportController/salesReport';
$route['(:any)/cylinder_sales_report'] = 'lpg/SalesController/cylinder_sales_report';
$route['(:any)/customerSalesReport'] = 'lpg/SalesReportController/customerSalesReport';

$route['(:any)/pendingCheck'] = 'lpg/SalesReportController/pendingCheck';

$route['(:any)/productWiseSalesReport'] = 'lpg/SalesReportController/productWiseSalesReport';
$route['(:any)/sales_report_brand_wise']='lpg/SalesReportController/sales_report_brand_wise';
$route['(:any)/date_wise_product_sales_by_date']='lpg/SalesReportController/date_wise_product_sales_by_date';
$route['(:any)/customer_due']='lpg/SalesController/customer_due';
$route['(:any)/referenceSalesReport'] = 'lpg/SalesController/referenceSalesReport';
$route['(:any)/customerDashboard/(:any)'] = 'lpg/SalesController/customerDashboard/$2';
$route['(:any)/topSaleProduct'] = 'lpg/SalesController/topSaleProduct';

/* Finance Account start */
$route['(:any)/editChartOfAccount/(:any)'] = 'lpg/AccountController/editChartOfAccount/$2';
$route['(:any)/chartOfAccount'] = 'lpg/AccountController/chartOfAccount';
$route['(:any)/listChartOfAccount'] = 'lpg/AccountController/listChartOfAccount';
$route['(:any)/viewChartOfAccount'] = 'lpg/AccountController/viewChartOfAccount';

$route['(:any)/openingBalance'] = 'lpg/FinaneController/openingBalance3';
$route['(:any)/openingBalance2'] = 'lpg/FinaneController/openingBalance2';
$route['(:any)/openingBalance3'] = 'lpg/FinaneController/openingBalance3';
$route['(:any)/openingBalance/(:any)'] = 'lpg/FinaneController/openingBalance/$2';
$route['(:any)/deleteOpneningBalance'] = 'lpg/FinaneController/deleteOpneningBalance';
$route['(:any)/customerLedger'] = 'lpg/AccountReportController/customerLedger';
$route['(:any)/supplierLedger'] = 'lpg/FinaneController/supplierLedger';
//payment Voucher

$route['(:any)/supplierOpening'] = 'lpg/FinaneController/supplierOpening';
$route['(:any)/supplierOpeningAdd'] = 'lpg/FinaneController/supplierOpeningAdd';
$route['(:any)/customerOpneing'] = 'lpg/FinaneController/customerOpneing';
$route['(:any)/customerOpneingAdd'] = 'lpg/FinaneController/customerOpneingAdd';
$route['(:any)/customerOpneingEdit/(:any)'] = 'lpg/FinaneController/customerOpneingEdit/$2';
$route['(:any)/supplierOpeningImport'] = 'lpg/FinaneController/supplierOpeningImport';
$route['(:any)/customerOpeningImport'] = 'lpg/FinaneController/customerOpeningImport';







//Report Voucher
$route['(:any)/trialBalance'] = 'lpg/FinaneController/trialBalance';
$route['(:any)/generalLedger'] = 'lpg/AccountReportController/generalLedger';
$route['(:any)/generalLedger2'] = 'lpg/AccountReportController/generalLedger2';
$route['(:any)/generalLedger/(:any)'] = 'lpg/FinaneController/generalLedger/$2';


$route['(:any)/balanceSheet2'] =  'lpg/FinaneController/balanceSheet';
//$route['(:any)/balanceSheet'] ='lpg/AccountController/balanceSheet';
$route['(:any)/balanceSheet'] ='lpg/AccountController/balanceSheetWithAllBranch';
$route['(:any)/balanceSheetWithBranch'] ='lpg/AccountController/balanceSheetWithBranch';
$route['(:any)/balanceSheetWithAllBranch'] ='lpg/AccountController/balanceSheetWithAllBranch';


$route['(:any)/incomeStetement2'] = 'lpg/FinaneController/incomeStetement';
$route['(:any)/incomeStetement'] = 'lpg/AccountController/incomeStatementWithAllBranch';
//$route['(:any)/incomeStetement'] = 'lpg/AccountController/incomeStatementWithBranch';
$route['(:any)/incomeStatementWithBranch'] = 'lpg/AccountController/incomeStatementWithBranch';
$route['(:any)/incomeStatementWithAllBranch'] = 'lpg/AccountController/incomeStatementWithAllBranch';

$route['(:any)/cashFlow'] = 'lpg/FinaneController/cashFlow';
$route['(:any)/cashBook'] = 'lpg/AccountReportController/cashBook';
$route['(:any)/bankBook'] = 'lpg/AccountReportController/bankBook';
$route['(:any)/detailsLedger/(:any)'] = 'lpg/FinaneController/detailsLedger/$2';

//bill voucher
$route['(:any)/billInvoice'] = 'lpg/FinaneController/billInvoice';
$route['(:any)/ServerFilterBillInvoice'] = 'lpg/ServerFilterController/billVoucherList';
$route['(:any)/billInvoice_add'] = 'lpg/FinaneController/billInvoice_add';
$route['(:any)/billInvoicePayment/(:any)'] = 'lpg/FinaneController/billInvoicePayment/$2';
$route['(:any)/billInvoice_view/(:any)'] = 'lpg/FinaneController/billInvoice_view/$2';
$route['(:any)/billInvoice_edit/(:any)'] = 'lpg/FinaneController/billInvoice_edit/$2';

//Receive Voucher
//$route['(:any)/receiveVoucher'] = 'lpg/FinaneController/receiveVoucher';
$route['(:any)/receiveVoucher'] = 'lpg/VoucherController/receiveVoucher';

$route['(:any)/ServerFilterReceiveVoucher'] = 'lpg/ServerFilterController/receiveList';
$route['(:any)/receiveVoucherAdd'] = 'lpg/VoucherController/receiveVoucherAdd';
$route['(:any)/receiveVoucherAdd/(:any)'] = 'lpg/VoucherController/receiveVoucherAdd/$2';
$route['(:any)/receiveVoucherEdit/(:any)'] = 'lpg/VoucherController/receiveVoucherEdit/$2';
$route['(:any)/receiveVoucherView/(:any)'] = 'lpg/VoucherController/receiveVoucherView/$2';
//Journal Voucher


$route['(:any)/ServerFilterJournalVoucher'] = 'lpg/ServerFilterController/journalList';

$route['(:any)/journalVoucherView/(:any)'] = 'lpg/VoucherController/journalVoucherView/$2';

$route['(:any)/journalVoucherEdit/(:any)'] = 'lpg/VoucherController/journalVoucherEdit/$2';

// al_mamun $route['(:any)/journalVoucherView/(:any)'] = 'lpg/FinaneController/journalVoucherView/$2';
// al_mamun $route['(:any)/journalVoucherEdit/(:any)'] = 'lpg/FinaneController/journalVoucherEdit/$2';



$route['(:any)/inventoryAdjustment'] = 'lpg/OpeningController/inventoryAdjustment';
$route['(:any)/inventoryAdjustmentAdd'] = 'lpg/OpeningController/inventoryAdjustmentAdd';
$route['(:any)/viewAdjustment/(:any)'] = 'lpg/OpeningController/viewAdjustment/$2';
$route['(:any)/openigInventoryImport'] = 'lpg/OpeningController/openigInventoryImport';
$route['(:any)/setupImport'] = 'lpg/ImportController/setupImport';

$route['(:any)/productImport'] = 'lpg/ImportController/productImport';
$route['(:any)/productImport_motorcycle'] = 'lpg/ImportController/productImport_motorcycle';
$route['(:any)/saveImportProduct'] = 'lpg/ImportController/saveImportProduct';
$route['(:any)/viewMoneryReceiptSup/(:any)'] = 'lpg/InventoryController/viewMoneryReceiptSup/$2';
$route['(:any)/viewMoneryReceiptSup/(:any)/(:any)'] = 'lpg/InventoryController/viewMoneryReceiptSup/$2/$2'; 

$route['(:any)/mpdf']='MpdfController';

//home controller start
$route['(:any)/userAccess'] = 'lpg/HomeController/userAccess';
$route['(:any)/adminLoginHistory'] = 'lpg/HomeController/adminLoginHistory';
$route['(:any)/(:any)/fortest/(:any)'] = 'lpg/SalesController/salesInvoice_add/$2';
$route['(:any)/(:any)/salesInvoice_add'] = 'lpg/SalesController/salesInvoice_add';
//home controller end
/* Finance Account End */


$route['(:any)/salesReturn'] = 'lpg/ReturnDagameController/salesReturn';
$route['(:any)/salesReturnAdd'] = 'lpg/ReturnDagameController/salesReturnAdd';
$route['(:any)/showAllInvoiceListByDate'] = 'lpg/ReturnDagameController/showAllInvoiceListByDate';
$route['(:any)/getInvoiceProductList'] = 'lpg/ReturnDagameController/getInvoiceProductList';
$route['(:any)/viewSalesReturn/(:any)'] = 'lpg/ReturnDagameController/viewSalesReturn/$2';

$route['(:any)/damageProduct'] = 'lpg/ReturnDagameController/damageProduct';
$route['(:any)/damageProductAdd'] = 'lpg/ReturnDagameController/damageProductAdd';
$route['(:any)/deleteDamageProduct/(:any)'] = 'lpg/ReturnDagameController/deleteDamageProduct/$2';

/*Accounting Voucher */

$route['(:any)/paymentVoucher'] = 'lpg/VoucherController/paymentVoucher';
//$route['(:any)/day_book_report'] = 'lpg/AccountReportController/day_book_report';
$route['(:any)/day_book_report'] = 'lpg/AccountReportController/day_book_report_with_all_branch';



$route['(:any)/ServerFilterPaymentVoucher'] = 'lpg/ServerFilterController/paymentList';
$route['(:any)/paymentVoucherAdd'] = 'lpg/VoucherController/paymentVoucherAdd';
$route['(:any)/paymentVoucherEdit/(:any)'] = 'lpg/VoucherController/paymentVoucherEdit/$2';

$route['(:any)/paymentVoucherAdd/(:any)'] = 'lpg/FinaneController/paymentVoucherAdd/$2';

$route['(:any)/paymentVoucherView/(:any)'] = 'lpg/VoucherController/paymentVoucherView/$2';

$route['(:any)/journalVoucherAdd'] = 'lpg/VoucherController/journalVoucherAdd';

$route['(:any)/journalVoucher'] = 'lpg/VoucherController/journalVoucher';

/*Accounting Voucher */
$route['(:any)/purchases_lpg_add'] = 'lpg/PurchaseLpgController/purchases_lpg_add';
$route['(:any)/viewPurchasesCylinder'] = 'lpg/PurchaseLpgController/viewPurchasesCylinder/$2';
$route['(:any)/purchases_cylinder_list'] = 'lpg/PurchaseLpgController/purchases_cylinder_list';
$route['(:any)/viewPurchasesCylinder/(:any)'] = 'lpg/PurchaseLpgController/viewPurchasesCylinder/$2';
$route['(:any)/purchases_lpg_edit/(:any)'] = 'lpg/PurchaseEditLpgController/purchases_lpg_edit/$2';



$route['(:any)/salesLpgInvoice_add'] = 'lpg/SalesLpgController/salesLpgInvoice_add';
$route['(:any)/salesInvoice_edit/(:any)'] = 'lpg/SalesInvoiceEditController/salesInvoice_edit/$2';
$route['(:any)/viewLpgCylinder/(:any)'] = 'lpg/SalesLpgController/salesInvoice_view/$2';
$route['(:any)/viewLpgCylinder_pdf/(:any)'] = 'lpg/SalesLpgController/viewLpgCylinder_pdf/$2';
$route['(:any)/viewLpgCylinder2/(:any)'] = 'lpg/SalesLpgController/salesInvoice_view2/$2';
$route['(:any)/salesInvoiceLpg'] = 'lpg/SalesLpgController/salesInvoice';

/* Branch info route*/
/*By All-Mamun 10/09/209*/
//$route['(:any)/branchInfo'] = 'lpg/BranchController/branchInfo';

$route['(:any)/branchInfo'] = 'lpg/BranchController/branchInfo';
$route['(:any)/branchInfo/(:any)'] = 'lpg/BranchController/branchInfo/$2';
$route['(:any)/deleteBranch/(:any)'] = 'lpg/BranchController/deleteBranch/$2';
/**/
$route['(:any)/bankAccountInfo'] = 'lpg/BankBranchController/bankAccountInfo';
$route['(:any)/bankAccountInfo/(:any)'] = 'lpg/BankBranchController/bankAccountInfo/$2';
$route['(:any)/deletebankAccountInfo/(:any)'] = 'lpg/BankBranchController/deletebankAccountInfo/$2';

$route['(:any)/paymentReceiveVoucherReport'] = 'lpg/FinaneController/paymentReceiveVoucherReport';



$route['(:any)/customerCylinderDueAdvance'] = 'lpg/CylinderDueAdvanceController/customer_cylinder_due_advance';



$route['(:any)/save_openning_balance_to_main_table'] = 'lpg/OpeningController/save_openning_balance_to_main_table';




$route['(:any)/sales_return_by_purchase'] = 'lpg/PurchaseLpgController/purchases_lpg_add';
$route['(:any)/InventoryAdjustmentWithAccount'] = 'lpg/InventoryAdjustmentController/index';
$route['(:any)/InventoryAdjustmentList'] = 'lpg/InventoryAdjustmentController/InventoryAdjustmentList';
$route['(:any)/invoiceAdjustmentShow/(:any)'] = 'lpg/InventoryAdjustmentController/invoiceAdjustmentShow/$2';
$route['(:any)/invoiceAdjustmentDelete/(:any)'] = 'lpg/InventoryAdjustmentController/invoiceAdjustmentDelete/$2';
$route['(:any)/InventoryAdjustmentEdit/(:any)'] = 'lpg/InventoryAdjustmentController/InventoryAdjustmentEdit/$2';
$route['(:any)/cylinderTypeReport'] = 'lpg/InventoryController/cylinderTypeReport';
$route['(:any)/cylinderTypeReport/(:any)'] = 'lpg/InventoryController/cylinderTypeReport2/$2';


$route['(:any)/route_info'] = 'lpg/CustomerRoute/route_info';
$route['(:any)/route_info_edit/(:any)'] = 'lpg/CustomerRoute/route_info_edit/$2';



$route['(:any)/inventoryNewAdd'] = 'lpg/IncentiveController/inventoryNewAdd';
$route['(:any)/newProductAddList'] = 'lpg/IncentiveController/newProductAddList';
$route['(:any)/newProductDelete/(:any)'] = 'lpg/IncentiveController/newProductDelete/$2';
$route['(:any)/newProductDeleteSingle/(:any)'] = 'lpg/IncentiveController/newProductDeleteSingle/$2';
$route['(:any)/invoiceView/(:any)'] = 'lpg/IncentiveController/invoiceView/$2';
$route['(:any)/editNewProduct/(:any)'] = 'lpg/IncentiveController/editNewProduct/$2';
$route['(:any)/IncentiveCheck'] = 'lpg/IncentiveController/IncentiveCheck';



$route['(:any)/root_info'] = 'lpg/RootController/root_info';
$route['(:any)/root_info_insert'] = 'lpg/RootController/root_info_insert';
$route['(:any)/root_info_delete/(:any)'] = 'lpg/RootController/root_info_delete/$2';
$route['(:any)/root_info_edit/(:any)'] = 'lpg/RootController/root_info_edit/$2';
$route['(:any)/root_info_update'] = 'lpg/RootController/root_info_update';
$route['(:any)/status/(:any)'] = 'lpg/RootController/status/$2';
$route['(:any)/status2/(:any)'] = 'lpg/RootController/status2/$2';
$route['(:any)/rootWiseSalesReport'] = 'lpg/SalesReportController/rootWiseSalesReport';



$route['(:any)/setupImportExcelCustomer'] = 'lpg/ImportExcelController/setupImportExcelCustomer';



//SubCategory
$route['(:any)/subCategory'] = 'lpg/InvProductPropertyController/subCategory';
$route['(:any)/subCatEdit/(:any)'] = 'lpg/InvProductPropertyController/subCatEdit/$2)';
$route['(:any)/subCatDelete/(:any)'] = 'lpg/InvProductPropertyController/subCatDelete/$2)';
$route['(:any)/statusSubCat/(:any)'] = 'lpg/InvProductPropertyController/statusSubCat/$2)';
$route['(:any)/statusSubCat2/(:any)'] = 'lpg/InvProductPropertyController/statusSubCat2/$2)';
//Model
$route['(:any)/modelAdd'] = 'lpg/InvProductPropertyController/modelAdd';
$route['(:any)/modelEdit/(:any)'] = 'lpg/InvProductPropertyController/modelEdit/$2)';
$route['(:any)/modelDelete/(:any)'] = 'lpg/InvProductPropertyController/modelDelete/$2)';
$route['(:any)/statusModel/(:any)'] = 'lpg/InvProductPropertyController/statusModel/$2)';
$route['(:any)/statusModel2/(:any)'] = 'lpg/InvProductPropertyController/statusModel2/$2)';
//colorAdd
$route['(:any)/colorAdd'] = 'lpg/InvProductPropertyController/colorAdd';
$route['(:any)/colorEdit/(:any)'] = 'lpg/InvProductPropertyController/colorEdit/$2)';
$route['(:any)/colorDelete/(:any)'] = 'lpg/InvProductPropertyController/colorDelete/$2)';
$route['(:any)/statusColor/(:any)'] = 'lpg/InvProductPropertyController/statusColor/$2)';
$route['(:any)/statusColor2/(:any)'] = 'lpg/InvProductPropertyController/statusColor2/$2)';

//sizeAdd
$route['(:any)/sizeAdd'] = 'lpg/InvProductPropertyController/sizeAdd';
$route['(:any)/sizeEdit/(:any)'] = 'lpg/InvProductPropertyController/sizeEdit/$2)';
$route['(:any)/sizeDelete/(:any)'] = 'lpg/InvProductPropertyController/sizeDelete/$2)';
$route['(:any)/statusSize/(:any)'] = 'lpg/InvProductPropertyController/statusSize/$2)';
$route['(:any)/statusSize2/(:any)'] = 'lpg/InvProductPropertyController/statusSize2/$2)';


$route['(:any)/product_property_set'] = 'lpg/InvProductPropertyController/product_property_set';


$route['(:any)/warranty_claim_voucher'] = 'lpg/WarrantyManagementController/warranty_claim_voucher';
$route['(:any)/warranty_claim_voucher_list'] = 'lpg/WarrantyManagementController/warranty_claim_voucher_list';
$route['(:any)/warranty_claim_voucher_view/(:any)'] = 'lpg/WarrantyManagementController/warranty_claim_voucher_view/$2';

$route['(:any)/warranty_receipt_voucher'] = 'lpg/WarrantyManagementController/warranty_receipt_voucher';
$route['(:any)/warranty_receipt_voucher_list'] = 'lpg/WarrantyManagementController/warranty_receipt_voucher_list';
$route['(:any)/warranty_receipt_voucher_view/(:any)'] = 'lpg/WarrantyManagementController/warranty_receipt_voucher_view/$2';


$route['(:any)/purchaseReturnAdd'] = 'lpg/ReturnDagameController/purchaseReturnAdd';
$route['(:any)/purchaseReturn'] = 'lpg/ReturnDagameController/purchaseReturn';
$route['(:any)/viewPurchaseReturn/(:any)'] = 'lpg/ReturnDagameController/viewPurchaseReturn/$2';



$route['(:any)/sales_order'] = 'lpg/SalesOrderController/sales_order_add';
$route['(:any)/sales_delivery_challan'] = 'lpg/SalesDeliveryChallanController/sales_delivery_challan';
$route['(:any)/sales_delivery_challan_list'] = 'lpg/SalesDeliveryChallanController/sdc_list';
$route['(:any)/sales_invoice_against_sales_delivery_chalan'] = 'lpg/SalesOrderController/sales_invoice_against_sales_delivery_chalan';

$route['(:any)/sales_order_list'] = 'lpg/SalesOrderController/sales_order_list';
$route['(:any)/so_po_view/(:any)'] = 'lpg/SalesOrderController/so_po_view/$2';
$route['(:any)/sales_order_edit/(:any)'] = 'lpg/SalesOrderController/sales_order_edit/$2';
//purchase order
$route['(:any)/purchase_order'] = 'lpg/PurchaseOrderController/purchase_order_add';
$route['(:any)/purchase_order_list'] = 'lpg/PurchaseOrderController/purchase_order_list';
$route['(:any)/purchase_order_view/(:any)'] = 'lpg/PurchaseOrderController/purchase_order_view/$2';
$route['(:any)/purchase_order_edit/(:any)'] = 'lpg/PurchaseOrderController/purchase_order_edit/$2';



$route['(:any)/material_received_note'] = 'lpg/MaterialReceivedController/material_received_note_add';
$route['(:any)/material_received_note_list'] = 'lpg/MaterialReceivedController/mrn_list';
$route['(:any)/mrn_view/(:any)'] = 'lpg/MaterialReceivedController/mrn_view/$2';
$route['(:any)/material_received_note_edit/(:any)'] = 'lpg/MaterialReceivedController/mrn_edit/$2';
$route['(:any)/purchase_invoice_against_mrn'] = 'lpg/MaterialReceivedController/purchase_invoice_against_mrn';
$route['(:any)/prepare_bill/(:any)'] = 'lpg/MaterialReceivedController/material_received_note_prepare_bill/$2';
$route['(:any)/prepare_sdc_bill/(:any)'] = 'lpg/SalesDeliveryChallanController/material_received_note_prepare_bill/$2';
$route['(:any)/view_mrn_bill/(:any)'] = 'lpg/MaterialReceivedController/view_mrn_bill/$2';
$route['(:any)/view_sdc_bill/(:any)'] = 'lpg/SalesDeliveryChallanController/view_sdc_bill/$2';
$route['(:any)/sdc_view/(:any)'] = 'lpg/SalesDeliveryChallanController/sdc_view/$2';


//salesInvoiceAgainstSO
$route['(:any)/salesInvoiceAgainstSO'] = 'lpg/SalesOrderController/sales_invoice_against_sales_order_add';
//purchaseInvoiceAgainstPO
$route['(:any)/purchaseInvoiceAgainstPO'] = 'lpg/PurchaseOrderController/purchase_invoice_against_purchase_order_add';
$route['(:any)/404_override'] = '';
$route['(:any)/translate_uri_dashes'] = FALSE;








//employee
$route['(:any)/employeeList'] = 'lpg/SetupController/employeeList';
$route['(:any)/employeeAdd'] = 'lpg/SetupController/employeeAdd';
$route['(:any)/employeeEdit/(:any)'] = 'lpg/SetupController/employeeEdit/$2)';
$route['(:any)/employeeDelete/(:any)'] = 'lpg/SetupController/employeeDelete/$2';

$route['(:any)/employeeImport'] = 'lpg/ImportController/employeeImport';
$route['(:any)/saveImportProduct'] = 'lpg/ImportController/saveImportProduct';
$route['(:any)/saveImportEmplloyee'] = 'lpg/ImportController/saveImportEmplloyee';
$route['(:any)/deleteImportEmployee/(:any)'] = 'lpg/ImportController/deleteImportEmployee/$2';


//hr Database Report
$route['(:any)/hrDatabaseReport'] = 'lpg/hrDatabaseReportController/hrDatabaseReport';

$route['(:any)/employeeSalaryList'] = 'lpg/SetupController/employeeSalaryList';
$route['(:any)/employeeSalaryAdd'] = 'lpg/SetupController/employeeSalaryAdd';
$route['(:any)/salaryEditByVoucher/(:any)'] = 'lpg/SetupController/salaryEditByVoucher/$2';
$route['(:any)/salaryViewVoucher/(:any)'] = 'lpg/SetupController/salaryViewVoucher/$2';
$route['(:any)/salaryDeleteVoucher/(:any)'] = 'lpg/SetupController/salaryDeleteVoucher/$2';
$route['(:any)/salaryEdit/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryEdit/$2/$3/$4';
$route['(:any)/salaryView/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryView/$2/$3/$4';
$route['(:any)/salaryDelete/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryDelete/$2/$3/$4';
$route['(:any)/salaryViewPdf/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryViewPdf/$2/$3/$4';
$route['(:any)/salaryViewCashPdf/(:any)/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryViewCashPdf/$2/$3/$4/$5';
$route['(:any)/salaryViewBankPdf/(:any)/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryViewBankPdf/$2/$3/$4/$5';

$route['(:any)/salaryViewByCash/(:any)/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryViewByCash/$2/$3/$4/$5';
$route['(:any)/salaryViewByBank/(:any)/(:any)/(:any)/(:any)'] = 'lpg/SetupController/salaryViewByBank/$2/$3/$4/$5';

//department departmentAdd
$route['(:any)/departmentList'] = 'myController/departmentList';
$route['(:any)/departmentAdd'] = 'myController/departmentAdd';
$route['(:any)/departmentEdit/(:any)'] = 'myController/departmentEdit/$2)';
$route['(:any)/departmentDelete/(:any)'] = 'myController/departmentDelete/$2)';
$route['(:any)/statusDepartment/(:any)'] = 'myController/statusDepartment/$2)';
$route['(:any)/statusDepartment2/(:any)'] = 'myController/statusDepartment2/$2)';

//Designation
$route['(:any)/designationAdd'] = 'myController/designationAdd';
$route['(:any)/designationEdit/(:any)'] = 'myController/designationEdit/$2)';
$route['(:any)/designationDelete/(:any)'] = 'myController/designationDelete/$2)';
$route['(:any)/statusdesignationDepartment/(:any)'] = 'myController/statusdesignationDepartment/$2)';
$route['(:any)/statusdesignationDepartment2/(:any)'] = 'myController/statusdesignationDepartment2/$2)';


$route['(:any)/employeeVoucherAdd'] = 'myController/employeeVoucherAdd';
$route['(:any)/employeeVoucherAdd/(:any)'] = 'myController/employeeVoucherAdd/$2';
$route['(:any)/employeeVoucher'] = 'myController/employeeVoucher';
$route['(:any)/employeeVoucherView/(:any)'] = 'myController/employeeVoucherView/$2';
$route['(:any)/employeeVoucherEdit/(:any)'] = 'myController/employeeVoucherEdit/$2';
$route['(:any)/salaryVoucher'] = 'lpg/SetupController/salaryVoucherReport';
//configuraatuion
$route['(:any)/employeeSalaryConfiquration'] = 'lpg/EmployeeSalaryConfigureController/employeeSalaryConfiquration';
$route['(:any)/employeeConfiquration'] = 'lpg/EmployeeConfiqureController/employeeConfiquration';
$route['(:any)/voucherConfiquration'] = 'lpg/VoucherConfigureController/voucherConfiquration';
$route['(:any)/voucherConfigEdit/(:any)'] = 'lpg/VoucherConfigureController/voucherConfigEdit/$2';

$route['(:any)/overTimeConfiquration'] = 'hr/OverTimeConfigureController/overTimeConfiquration';
$route['(:any)/overTimeConfigEdit/(:any)'] = 'hr/OverTimeConfigureController/overTimeConfigEdit/$2';

//hr menu

$route['(:any)/attendenceReport'] = 'lpg/SetupController/attendenceReport';
$route['(:any)/hrPerformanceReport'] = 'lpg/SetupController/hrPerformanceReport';
$route['(:any)/departmentWiseSummeryReport'] = 'hr/DepartmentSummaryReport/departmentWiseSummeryReport';


$route['(:any)/addFestivalBonus'] = 'lpg/FestivalBonusController/addFestivalBonus';
$route['(:any)/festivalVoucherList'] = 'lpg/FestivalBonusController/festivalVoucherList';
$route['(:any)/festivalVoucherEdit/(:any)'] = 'lpg/FestivalBonusController/festivalVoucherEdit/$2';
$route['(:any)/festivalVoucherView/(:any)'] = 'lpg/FestivalBonusController/festivalVoucherView/$2';
$route['(:any)/festivalVoucherDelete/(:any)'] = 'lpg/FestivalBonusController/festivalVoucherDelete/$2';
$route['(:any)/festivalVoucherReport'] = 'lpg/FestivalBonusController/festivalVoucherReport';

//Performance Bonus
$route['(:any)/addPerformanceBonus'] = 'hr/PerformanceBonusController/addPerformanceBonus';
$route['(:any)/PerformanceBonusList'] = 'hr/PerformanceBonusController/PerformanceBonusList';
$route['(:any)/PerformanceBonusEdit/(:any)'] = 'hr/PerformanceBonusController/PerformanceBonusEdit/$2';
$route['(:any)/PerformanceBonusView/(:any)'] = 'hr/PerformanceBonusController/PerformanceBonusView/$2';
$route['(:any)/PerformanceBonusDelete/(:any)'] = 'hr/PerformanceBonusController/PerformanceBonusDelete/$2';
$route['(:any)/PerformanceBonusReport'] = 'hr/PerformanceBonusController/PerformanceBonusReport';

//Leave Payment
$route['(:any)/addLeavePayment'] = 'hr/LeavePaymentController/addLeavePayment';
$route['(:any)/LeavePaymentList'] = 'hr/LeavePaymentController/LeavePaymentList';
$route['(:any)/LeavePaymentEdit/(:any)'] = 'hr/LeavePaymentController/LeavePaymentEdit/$2';
$route['(:any)/LeavePaymentView/(:any)'] = 'hr/LeavePaymentController/LeavePaymentView/$2';
$route['(:any)/LeavePaymentDelete/(:any)'] = 'hr/LeavePaymentController/LeavePaymentDelete/$2';
$route['(:any)/LeavePaymentReport'] = 'hr/LeavePaymentController/LeavePaymentReport';

//OverTime
$route['(:any)/addOverTimePayment'] = 'hr/OverTimePaymentController/addOverTimePayment';
$route['(:any)/overTimePaymentList'] = 'hr/OverTimePaymentController/overTimePaymentList';
$route['(:any)/overTimePaymentEdit/(:any)'] = 'hr/OverTimePaymentController/overTimePaymentEdit/$2';
$route['(:any)/overTimePaymentView/(:any)'] = 'hr/OverTimePaymentController/overTimePaymentView/$2';
$route['(:any)/overTimePaymentDelete/(:any)'] = 'hr/OverTimePaymentController/overTimePaymentDelete/$2';
$route['(:any)/overTimePaymentReport'] = 'hr/OverTimePaymentController/overTimePaymentReport';

//Attendance
$route['(:any)/empAttendance'] = 'hr/attendanceController/empAttendance';
$route['(:any)/save_attendance'] = 'hr/attendanceController/save_attendance';
$route['(:any)/attendance_report'] = 'hr/attendanceController/attendance_report';
$route['(:any)/get_report'] = 'hr/attendanceController/get_report';

$route['(:any)/set_working_days'] = 'hr/attendanceController/set_working_days';
$route['(:any)/save_working_days'] = 'hr/attendanceController/save_working_days';
$route['(:any)/holiday_list'] = 'hr/attendanceController/holiday_list';
$route['(:any)/holiday_list/(:any)'] = 'hr/attendanceController/holiday_list/$2';
$route['(:any)/save_holiday'] = 'hr/attendanceController/save_holiday';
$route['(:any)/delete_holiday_list/(:any)'] = 'hr/attendanceController/delete_holiday_list/$2';
$route['(:any)/leave_category/(:any)'] = 'hr/attendanceController/leave_category/$2';
$route['(:any)/leave_category'] = 'hr/attendanceController/leave_category';
$route['(:any)/save_leave_category'] = 'hr/attendanceController/save_leave_category';
$route['(:any)/delete_leave_category/(:any)'] = 'hr/attendanceController/delete_leave_category/$2';





