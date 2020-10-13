describe('Project - project deletion', function () {
  it('should let an employee delete a project as administrator', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createProject(1, 'project 1')
    cy.visit('/1/projects/1')
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=cancel-button]').click()
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=submit-delete-project-button]').click()
    cy.url().should('include', '/1/projects')
    cy.wait(200)
  })

  it('should delete a project as hr', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.changePermission(1, 200)

    // make sure we can delete a project with only the name of the project
    cy.createProject(1, 'project 1')
    cy.visit('/1/projects/1')
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=cancel-button]').click()
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=submit-delete-project-button]').click()
    cy.url().should('include', '/1/projects')
    cy.wait(200)
  })

  it('should delete a project as normal user', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.changePermission(1, 300)

    // make sure we can delete a project with only the name of the project
    cy.createProject(1, 'project 1')
    cy.visit('/1/projects/1')
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=cancel-button]').click()
    cy.get('[data-cy=project-delete]').click()
    cy.get('[data-cy=submit-delete-project-button]').click()
    cy.url().should('include', '/1/projects')
  })
})
