use('vite_gourmand');

db.menu_statistics.deleteMany({});
db.monthly_statistics.deleteMany({});
db.menu_monthly_statistics.deleteMany({});
db.dashboard_statistics.deleteMany({});

db.menu_statistics.insertMany([
  {
    menuId: NumberInt(1),
    menuTitle: 'Menu Noël Tradition',
    orders: NumberInt(38),
    revenue: 29990.00,
    averageBasket: 789.21,
    averageRating: 4.8,
    lastOrder: ISODate('2026-12-24T18:30:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(2),
    menuTitle: 'Menu Saint-Valentin',
    orders: NumberInt(18),
    revenue: 52320.00,
    averageBasket: 2906.67,
    averageRating: 4.9,
    lastOrder: ISODate('2026-09-12T17:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(3),
    menuTitle: 'Menu Terre & Mer',
    orders: NumberInt(32),
    revenue: 38278.61,
    averageBasket: 1196.21,
    averageRating: 4.6,
    lastOrder: ISODate('2026-07-18T20:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(4),
    menuTitle: 'Menu Végé-Gourmand',
    orders: NumberInt(26),
    revenue: 8254.63,
    averageBasket: 317.49,
    averageRating: 4.4,
    lastOrder: ISODate('2026-06-18T12:30:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(5),
    menuTitle: 'Menu Pâques en Famille',
    orders: NumberInt(45),
    revenue: 13876.55,
    averageBasket: 308.37,
    averageRating: 4.5,
    lastOrder: ISODate('2026-06-15T13:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(6),
    menuTitle: 'Menu Cocktail Bordelais',
    orders: NumberInt(32),
    revenue: 42262.00,
    averageBasket: 1320.69,
    averageRating: 4.7,
    lastOrder: ISODate('2026-06-25T18:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  }
]);

db.monthly_statistics.insertMany([
  {
    month: '2026-01',
    revenue: 2740.00,
    orders: NumberInt(5),
    averageBasket: 548.00,
    bestSellingMenu: 'Menu Pâques en Famille',
    updatedAt: ISODate('2026-01-31T23:30:00Z')
  },
  {
    month: '2026-02',
    revenue: 4360.00,
    orders: NumberInt(7),
    averageBasket: 622.86,
    bestSellingMenu: 'Menu Terre & Mer',
    updatedAt: ISODate('2026-02-28T23:30:00Z')
  },
  {
    month: '2026-03',
    revenue: 7386.60,
    orders: NumberInt(14),
    averageBasket: 527.61,
    bestSellingMenu: 'Menu Pâques en Famille',
    updatedAt: ISODate('2026-03-31T23:30:00Z')
  },
  {
    month: '2026-04',
    revenue: 11875.50,
    orders: NumberInt(16),
    averageBasket: 742.22,
    bestSellingMenu: 'Menu Cocktail Bordelais',
    updatedAt: ISODate('2026-04-30T23:30:00Z')
  },
  {
    month: '2026-05',
    revenue: 17328.12,
    orders: NumberInt(21),
    averageBasket: 825.15,
    bestSellingMenu: 'Menu Saint-Valentin',
    updatedAt: ISODate('2026-05-31T23:30:00Z')
  },
  {
    month: '2026-06',
    revenue: 22108.84,
    orders: NumberInt(28),
    averageBasket: 789.60,
    bestSellingMenu: 'Menu Cocktail Bordelais',
    updatedAt: ISODate('2026-06-30T23:30:00Z')
  },
  {
    month: '2026-07',
    revenue: 19642.26,
    orders: NumberInt(20),
    averageBasket: 982.11,
    bestSellingMenu: 'Menu Terre & Mer',
    updatedAt: ISODate('2026-07-31T23:30:00Z')
  },
  {
    month: '2026-08',
    revenue: 15340.00,
    orders: NumberInt(13),
    averageBasket: 1180.00,
    bestSellingMenu: 'Menu Saint-Valentin',
    updatedAt: ISODate('2026-08-31T23:30:00Z')
  },
  {
    month: '2026-09',
    revenue: 28640.00,
    orders: NumberInt(18),
    averageBasket: 1591.11,
    bestSellingMenu: 'Menu Saint-Valentin',
    updatedAt: ISODate('2026-09-30T23:30:00Z')
  },
  {
    month: '2026-10',
    revenue: 12175.00,
    orders: NumberInt(12),
    averageBasket: 1014.58,
    bestSellingMenu: 'Menu Terre & Mer',
    updatedAt: ISODate('2026-10-31T23:30:00Z')
  },
  {
    month: '2026-11',
    revenue: 20975.47,
    orders: NumberInt(19),
    averageBasket: 1103.97,
    bestSellingMenu: 'Menu Noël Tradition',
    updatedAt: ISODate('2026-11-30T23:30:00Z')
  },
  {
    month: '2026-12',
    revenue: 22410.00,
    orders: NumberInt(18),
    averageBasket: 1245.00,
    bestSellingMenu: 'Menu Noël Tradition',
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  }
]);

const seededMenus = db.menu_statistics.find().sort({ menuId: 1 }).toArray();
const seededMonths = db.monthly_statistics.find().sort({ month: 1 }).toArray();
const totalMenuRevenue = seededMenus.reduce((total, menu) => total + Number(menu.revenue || 0), 0);
const totalMenuOrders = seededMenus.reduce((total, menu) => total + Number(menu.orders || 0), 0);
const menuMonthlyStatistics = [];

seededMonths.forEach((month) => {
  let remainingOrders = Number(month.orders || 0);
  let remainingRevenue = Number(month.revenue || 0);

  seededMenus.forEach((menu, index) => {
    const isLastMenu = index === seededMenus.length - 1;
    const orderShare = totalMenuOrders > 0 ? Number(menu.orders || 0) / totalMenuOrders : 0;
    const revenueShare = totalMenuRevenue > 0 ? Number(menu.revenue || 0) / totalMenuRevenue : 0;
    const orders = isLastMenu
      ? Math.max(0, remainingOrders)
      : Math.max(0, Math.floor(Number(month.orders || 0) * orderShare));
    const revenue = isLastMenu
      ? Math.max(0, Math.round(remainingRevenue * 100) / 100)
      : Math.max(0, Math.round(Number(month.revenue || 0) * revenueShare * 100) / 100);

    remainingOrders -= orders;
    remainingRevenue = Math.round((remainingRevenue - revenue) * 100) / 100;

    menuMonthlyStatistics.push({
      menuId: menu.menuId,
      menuTitle: menu.menuTitle,
      month: month.month,
      orders: NumberInt(orders),
      revenue,
      averageBasket: orders > 0 ? Math.round((revenue / orders) * 100) / 100 : 0,
      averageRating: menu.averageRating,
      lastOrder: ISODate(`${month.month}-28T12:00:00Z`),
      updatedAt: month.updatedAt
    });
  });
});

db.menu_monthly_statistics.insertMany(menuMonthlyStatistics);

db.dashboard_statistics.insertMany([
  {
    generatedAt: ISODate('2026-01-31T23:45:00Z'),
    totalRevenue: 2740.00,
    totalOrders: NumberInt(5),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Pâques en Famille',
    averageBasket: 548.00,
    averageRating: 4.3
  },
  {
    generatedAt: ISODate('2026-02-28T23:45:00Z'),
    totalRevenue: 4360.00,
    totalOrders: NumberInt(7),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Terre & Mer',
    averageBasket: 622.86,
    averageRating: 4.4
  },
  {
    generatedAt: ISODate('2026-03-31T23:45:00Z'),
    totalRevenue: 7386.60,
    totalOrders: NumberInt(14),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Pâques en Famille',
    averageBasket: 527.61,
    averageRating: 4.5
  },
  {
    generatedAt: ISODate('2026-04-30T23:45:00Z'),
    totalRevenue: 11875.50,
    totalOrders: NumberInt(16),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Cocktail Bordelais',
    averageBasket: 742.22,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-05-31T23:45:00Z'),
    totalRevenue: 17328.12,
    totalOrders: NumberInt(21),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Saint-Valentin',
    averageBasket: 825.15,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-06-30T23:45:00Z'),
    totalRevenue: 22108.84,
    totalOrders: NumberInt(28),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Cocktail Bordelais',
    averageBasket: 789.60,
    averageRating: 4.7
  },
  {
    generatedAt: ISODate('2026-07-31T23:45:00Z'),
    totalRevenue: 19642.26,
    totalOrders: NumberInt(20),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Terre & Mer',
    averageBasket: 982.11,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-08-31T23:45:00Z'),
    totalRevenue: 15340.00,
    totalOrders: NumberInt(13),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Saint-Valentin',
    averageBasket: 1180.00,
    averageRating: 4.8
  },
  {
    generatedAt: ISODate('2026-09-30T23:45:00Z'),
    totalRevenue: 28640.00,
    totalOrders: NumberInt(18),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Saint-Valentin',
    averageBasket: 1591.11,
    averageRating: 4.9
  },
  {
    generatedAt: ISODate('2026-10-31T23:45:00Z'),
    totalRevenue: 12175.00,
    totalOrders: NumberInt(12),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Terre & Mer',
    averageBasket: 1014.58,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-11-30T23:45:00Z'),
    totalRevenue: 20975.47,
    totalOrders: NumberInt(19),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Noël Tradition',
    averageBasket: 1103.97,
    averageRating: 4.7
  },
  {
    generatedAt: ISODate('2026-12-31T23:45:00Z'),
    totalRevenue: 22410.00,
    totalOrders: NumberInt(18),
    activeMenus: NumberInt(6),
    topMenu: 'Menu Noël Tradition',
    averageBasket: 1245.00,
    averageRating: 4.8
  }
]);
