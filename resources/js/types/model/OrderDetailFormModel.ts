interface OrderDetailFormModel {
    payment_method: PaymentMethodEntity | null;
    shipping_method: ShippingMethodEntity | null;
    province_id: string | null;
    city_id: string | null;
    province: ProvinceEntity | null;
    city: CityEntity | null;
    address: string | null;
    shipping_cost: any;
}
