describe('Adminland - Company news management', function () {
  it('should let user access company news adminland screen with the right permissions', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/news', 100, 'Company news')
    cy.canAccess('/1/account/news', 200, 'Company news')
    cy.canNotAccess('/1/account/news', 300)
  })
})
